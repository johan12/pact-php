<?php

namespace PhpPact\Consumer\Matcher;

/**
 * @deprecated use Match instead
 */
class Matcher
{
    /**
     * Alias for the `like()` function.
     *
     * @param $value
     *
     * @throws \Exception
     *
     * @return array
     */
    public function somethingLike($value): array
    {
        return Match::like($value);
    }

    /**
     * @param mixed $value example of what the expected data would be
     *
     * @throws \Exception
     *
     * @return array
     */
    public function like($value): array
    {
        return Match::like($value);
    }

    /**
     * Expect an array of similar data as the value passed in.
     *
     * @param mixed $value example of what the expected data would be
     * @param int   $min   minimum number of objects to verify against
     *
     * @return array
     */
    public function eachLike($value, int $min = 1): array
    {
        return Match::eachLike($value, $min);
    }

    /**
     * Validate that a value will match a regex pattern.
     *
     * @param mixed  $value   example of what the expected data would be
     * @param string $pattern valid Ruby regex pattern
     *
     * @throws \Exception
     *
     * @return array
     */
    public function term($value, string $pattern): array
    {
        return Match::term($value, $pattern);
    }

    /**
     * Alias for the term matcher.
     *
     * @param $value
     * @param string $pattern
     *
     * @throws \Exception
     *
     * @return array
     */
    public function regex($value, string $pattern)
    {
        return Match::term($value, $pattern);
    }

    /**
     * ISO8601 date format wrapper for the term matcher.
     *
     * @param string $value valid ISO8601 date, example: 2010-01-01
     *
     * @throws \Exception
     *
     * @return array
     */
    public function dateISO8601(string $value = '2013-02-01'): array
    {
        return Match::term($value, Match::ISO8601_DATE_FORMAT);
    }

    /**
     * ISO8601 Time Matcher, matches a pattern of the format "'T'HH:mm:ss".
     *
     * @param string $value
     *
     * @throws \Exception
     *
     * @return array
     */
    public function timeISO8601(string $value = 'T22:44:30.652Z'): array
    {
        return Match::term($value, Match::ISO8601_TIME_FORMAT);
    }

    /**
     * ISO8601 DateTime matcher.
     *
     * @param string $value
     *
     * @throws \Exception
     *
     * @return array
     */
    public function dateTimeISO8601(string $value = '2015-08-06T16:53:10+01:00'): array
    {
        return Match::term($value, Match::ISO8601_DATETIME_FORMAT);
    }

    /**
     * ISO8601 DateTime matcher with required millisecond precision.
     *
     * @param string $value
     *
     * @throws \Exception
     *
     * @return array
     */
    public function dateTimeWithMillisISO8601(string $value = '2015-08-06T16:53:10.123+01:00'): array
    {
        return Match::term($value, Match::ISO8601_DATETIME_WITH_MILLIS_FORMAT);
    }

    /**
     * RFC3339 Timestamp matcher, a subset of ISO8609.
     *
     * @param string $value
     *
     * @throws \Exception
     *
     * @return array
     */
    public function timestampRFC3339(string $value = 'Mon, 31 Oct 2016 15:21:41 -0400'): array
    {
        return Match::term($value, Match::RFC3339_TIMESTAMP_FORMAT);
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    public function boolean(): array
    {
        return Match::like(true);
    }

    /**
     * @param int $int
     *
     * @throws \Exception
     *
     * @return array
     */
    public function integer(int $int = 13): array
    {
        return Match::like($int);
    }

    /**
     * @param float $float
     *
     * @throws \Exception
     *
     * @return array
     */
    public function decimal(float $float = 13.01): array
    {
        return Match::like($float);
    }

    /**
     * @param string $hex
     *
     * @throws \Exception
     *
     * @return array
     */
    public function hexadecimal(string $hex = '3F'): array
    {
        return Match::term($hex, Match::HEX_FORMAT);
    }

    /**
     * @param string $uuid
     *
     * @throws \Exception
     *
     * @return array
     */
    public function uuid(string $uuid = 'ce118b6e-d8e1-11e7-9296-cec278b6b50a'): array
    {
        return Match::term($uuid, Match::UUID_V4_FORMAT);
    }

    /**
     * @param string $ip
     *
     * @throws \Exception
     *
     * @return array
     */
    public function ipv4Address(string $ip = '127.0.0.13'): array
    {
        return Match::term($ip, Match::IPV4_FORMAT);
    }

    /**
     * @param string $ip
     *
     * @throws \Exception
     *
     * @return array
     */
    public function ipv6Address(string $ip = '::ffff:192.0.2.128'): array
    {
        return Match::term($ip, Match::IPV6_FORMAT);
    }
}
