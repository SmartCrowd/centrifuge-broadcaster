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
     * Generates connection settings for centrifuge client
     *
     * @return array
     */
    public function getConnectionSettings()
    {
        $timestamp = time();
        $userId    = Auth::check() ? Auth::user()->id : '';
        $config    = config('broadcasting.centrifuge');

        return [
            'url'       => $config['url'],
            'project'   => $config['project'],
            'user'      => (string) $userId,
            'timestamp' => (string) $timestamp,
            'token'     => $this->generateToken($userId, $timestamp, $config)
        ];
    }

    /**
     * @param int|string $userId current user id, or empty string if no one logged in
     * @param int $timestamp
     * @param array $config Centrifuge broadcaster config
     * @return string
     */
    protected function generateToken($userId, $timestamp, $config)
    {
        $ctx = hash_init('sha256', HASH_HMAC, $config['projectSecret']);
        hash_update($ctx, $config['project']);
        hash_update($ctx, $userId);
        hash_update($ctx, $timestamp);

        return hash_final($ctx);
    }
}