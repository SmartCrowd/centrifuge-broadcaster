<?php

namespace SmartCrowd\Centrifuge;

class CentrifugeManagerTest extends TestCase
{
    public function testGenerateToken()
    {
        $generatedToken = CentrifugeFacade::generateToken(1, 1439311197, config('broadcasting.connections.centrifuge'));
        $this->assertSame('4a8648c63a6b5fd7210cda5d82a487b868280708fe21e133dc09f927460f9a5e', $generatedToken);
    }

    public function testGetConnection()
    {
        $connection = CentrifugeFacade::getConnection();

        $generatedToken = CentrifugeFacade::generateToken(
            '',
            CentrifugeFacade::getTimestamp(),
            config('broadcasting.connections.centrifuge')
        );

        $this->assertEquals([
            'url' => 'http://127.0.0.1:8000',
            'project' => 'test',
            'user' => '',
            'timestamp' => CentrifugeFacade::getTimestamp(),
            'token' => $generatedToken
        ], $connection);
    }

}