<?php

namespace PhpPact\Consumer\Model;

/**
 * Request/Response Pair to be posted to the Ruby Standalone Mock Server for PACT tests.
 * Class Interaction.
 */
class Interaction implements \JsonSerializable
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var null|string
     */
    private $providerState;

    /**
     * @var ConsumerRequest
     */
    private $request;

    /**
     * @var ProviderResponse
     */
    private $response;

    /**
     * @param string|null $description should not be nullable
     * @param ConsumerRequest|null $request should not be nullable
     * @param ProviderResponse|null $response should not be nullable
     * @param string|null $providerState
     */
    public function __construct(
        string $description = null,
        ConsumerRequest $request = null,
        ProviderResponse $response = null,
        string $providerState = null
    ) {
        $this->description = $description;
        $this->request = $request;
        $this->response = $response;
        $this->providerState = $providerState;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Interaction
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getProviderState(): ?string
    {
        return $this->providerState;
    }

    /**
     * @param string $providerState
     *
     * @return Interaction
     */
    public function setProviderState(string $providerState): self
    {
        $this->providerState = $providerState;

        return $this;
    }

    /**
     * @return ConsumerRequest
     */
    public function getRequest(): ConsumerRequest
    {
        return $this->request;
    }

    /**
     * @param ConsumerRequest $request
     *
     * @return Interaction
     */
    public function setRequest(ConsumerRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return ProviderResponse
     */
    public function getResponse(): ProviderResponse
    {
        return $this->response;
    }

    /**
     * @param ProviderResponse $response
     *
     * @return Interaction
     */
    public function setResponse(ProviderResponse $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        if ($this->getProviderState()) {
            return [
                'description'   => $this->getDescription(),
                'providerState' => $this->getProviderState(),
                'request'       => $this->getRequest(),
                'response'      => $this->getResponse(),
            ];
        }

        return [
                'description'   => $this->getDescription(),
                'request'       => $this->getRequest(),
                'response'      => $this->getResponse(),
            ];
    }
}
