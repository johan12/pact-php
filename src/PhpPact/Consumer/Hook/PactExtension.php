<?php

namespace PhpPact\Consumer\Hook;

use function getenv;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Uri;
use PhpPact\Broker\Service\BrokerHttpClient;
use PhpPact\Http\GuzzleClient;
use PhpPact\Standalone\MockService\MockServer;
use PhpPact\Standalone\MockService\MockServerConfigInterface;
use PhpPact\Standalone\MockService\MockServerEnvConfig;
use PhpPact\Standalone\MockService\Service\MockServerHttpService;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\AfterTestFailureHook;
use PHPUnit\Runner\BeforeFirstTestHook;
use Throwable;

class PactExtension implements BeforeFirstTestHook, AfterLastTestHook, AfterTestFailureHook
{
    /**
     * @var MockServer|null
     */
    private $server = null;

    /**
     * @var MockServerConfigInterface
     */
    private $mockServerConfig;

    /**
     * @var bool
     */
    private $failed = false;

    public function __construct()
    {
        $this->mockServerConfig = new MockServerEnvConfig();
    }

    public function executeBeforeFirstTest(): void
    {
        $this->server = new MockServer($this->mockServerConfig);
        $this->server->start();
    }

    public function executeAfterTestFailure(string $test, string $message, float $time): void
    {
        $this->failed = true;
    }

    public function executeAfterLastTest(): void
    {
        try {
            $httpService = new MockServerHttpService(new GuzzleClient(), $this->mockServerConfig);
            $httpService->verifyInteractions();

            $pact = $httpService->getPactJson();
        } catch (ServerException $exception) {
            print $exception->getResponse()->getBody()->getContents();
            exit(1);
        } catch (Throwable $throwable) {
            print $throwable->getMessage();
            exit(1);
        } finally {
            $this->server->stop();
        }

        $this->uploadPact($pact);
    }

    protected function uploadPact(string $pact): void
    {
        if ($this->failed === true) {
            print 'A unit test has failed. Skipping PACT file upload.';
            return;
        }

        if (!($pactBrokerUri = getenv('PACT_BROKER_URI'))) {
            print 'PACT_BROKER_URI environment variable was not set. Skipping PACT file upload.';
            return;
        }

        if (!($consumerVersion = getenv('PACT_CONSUMER_VERSION'))) {
            print 'PACT_CONSUMER_VERSION environment variable was not set. Skipping PACT file upload.';
            return;
        }

        if (!($tag = getenv('PACT_CONSUMER_TAG'))) {
            print 'PACT_CONSUMER_TAG environment variable was not set. Skipping PACT file upload.';
            return;
        }

        $clientConfig = [];
        if (($user = getenv('PACT_BROKER_HTTP_AUTH_USER')) &&
            ($pass = getenv('PACT_BROKER_HTTP_AUTH_PASS'))
        ) {
            $clientConfig = [
                'auth' => [$user, $pass],
            ];
        }

        if (($sslVerify = getenv('PACT_BROKER_SSL_VERIFY'))) {
            $clientConfig['verify'] = $sslVerify !== 'no';
        }

        $headers = [];
        if ($bearerToken = getenv('PACT_BROKER_BEARER_TOKEN')) {
            $headers['Authorization'] = 'Bearer ' . $bearerToken;
        }

        $client = new GuzzleClient($clientConfig);

        $brokerHttpService = new BrokerHttpClient($client, new Uri($pactBrokerUri), $headers);
        $brokerHttpService->tag($this->mockServerConfig->getConsumer(), $consumerVersion, $tag);
        $brokerHttpService->publishJson($pact, $consumerVersion);

        print 'Pact file has been uploaded to the Broker successfully.';
    }
}
