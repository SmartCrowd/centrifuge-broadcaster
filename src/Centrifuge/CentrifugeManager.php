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
     * Generates connection settings for centrifuge client
     *
     * @return array
     */
    public function getConnection()
    {
        $this->timestamp = time();

        $userId    = Auth::check() ? Auth::user()->id : '';
        $config    = config('broadcasting.connections.centrifuge');

        return [
            'url'       => $config['url'],
            'project'   => $config['project'],
            'user'      => (string) $userId,
            'timestamp' => (string) $this->timestamp,
            'token'     => $this->generateToken($userId, $this->timestamp, $config)
        ];
    }

    /**
     * @param int|string $userId current user id, or empty string if no one logged in
     * @param int $timestamp
     * @param array $config Centrifuge broadcaster config
     * @return string
     */
    public function generateToken($userId, $timestamp, $config)
    {
        $ctx = hash_init('sha256', HASH_HMAC, $config['projectSecret']);
        hash_update($ctx, $config['project']);
        hash_update($ctx, $userId);
        hash_update($ctx, $timestamp);

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