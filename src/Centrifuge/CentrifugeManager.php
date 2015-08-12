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
     * @var int Last used timestamp
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
     * @return array
     */
    public function getConnection()
    {
        $this->timestamp = time();

        $userId    = Auth::check() ? Auth::user()->id : '';

        return [
            'url'       => $this->config['baseUrl'],
            'project'   => $this->config['project'],
            'user'      => (string) $userId,
            'timestamp' => (string) $this->timestamp,
            'token'     => $this->generateToken($userId, $this->timestamp)
        ];
    }

    /**
     * @param int|string $userId current user id, or empty string if no one logged in
     * @param int $timestamp
     * @return string
     */
    public function generateToken($userId, $timestamp)
    {
        $ctx = hash_init('sha256', HASH_HMAC, $this->config['projectSecret']);
        hash_update($ctx, $this->config['project']);
        hash_update($ctx, $userId);
        hash_update($ctx, $timestamp);

        return hash_final($ctx);
    }

    public function generateApiSign($encodedData)
    {
        $ctx = hash_init('sha256', HASH_HMAC, $this->config['projectSecret']);
        hash_update($ctx, $this->config['project']);
        hash_update($ctx, $encodedData);

        return hash_final($ctx);
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}