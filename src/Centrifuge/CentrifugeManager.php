<?php

namespace SmartCrowd\Centrifuge;
use Illuminate\Support\Facades\Auth;

/**
 * Class CentrifugeManager
 * @package SmartCrowd\Centrifuge
 */
class CentrifugeManager
{
    /**
     * @var string Last used timestamp
     */
    protected $timestamp;

    /**
     * @var array
     */
    protected $config;

    public function __construct()
    {
        $this->config = config('broadcasting.connections.centrifuge');
    }

    /**
     * Generates connection settings for centrifuge client
     *
     * @param bool $isSockJS
     * @param array $options
     * @return array
     */
    public function getConnection($isSockJS = false, $options = [])
    {
        $this->timestamp = (string) time();

        $userId = Auth::check() ? (string) Auth::user()->id : '';

        $info = array_key_exists('info', $options) ? $options['info'] : '';

        return array_merge(
            $options,
            [
                'url'       => rtrim($this->config['baseUrl'], '/') . ($isSockJS ? '/connection' : ''),
                'user'      => $userId,
                'timestamp' => $this->timestamp,
                'token'     => $this->generateToken($userId, $this->timestamp, $info),
            ]
        );
    }

    /**
     * Generates client connection token
     *
     * @param string $userId current user id, or empty string if no one logged in
     * @param string $timestamp
     * @param string $info
     * @return string
     */
    public function generateToken($userId, $timestamp, $info = '')
    {
        $ctx = hash_init('sha256', HASH_HMAC, $this->config['secret']);
        hash_update($ctx, $userId);
        hash_update($ctx, $timestamp);
        hash_update($ctx, $info);

        return hash_final($ctx);
    }

    /**
     * Generates sign for server http api method calls
     *
     * @param $encodedData
     * @return string
     */
    public function generateApiSign($encodedData)
    {
        $ctx = hash_init('sha256', HASH_HMAC, $this->config['secret']);
        hash_update($ctx, $encodedData);

        return hash_final($ctx);
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}