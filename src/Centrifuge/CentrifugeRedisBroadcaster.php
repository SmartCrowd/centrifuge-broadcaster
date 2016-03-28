<?php

namespace SmartCrowd\Centrifuge;

use Predis\ClientInterface;

/**
 * Class CentrifugeBroadcaster
 * @package SmartCrowd\Centrifuge
 */
class CentrifugeRedisBroadcaster extends CentrifugeBaseBroadcaster
{
    /**
     * @var ClientInterface
     */
    protected $connection;

    /**
     * Create a new broadcaster instance.
     *
     * @param ClientInterface $connection
     */
    public function __construct(ClientInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Sends commands by pushing into redis list
     *
     * @param array $commands
     */
    protected function sendCommands($commands)
    {
        $centrifugeData = [
            'data' => $commands
        ];

        $this->connection->rpush('centrifugo.api', json_encode($centrifugeData));
    }
}