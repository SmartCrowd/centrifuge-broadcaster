<?php

namespace SmartCrowd\Centrifuge;

class CentrifugeManagerTest extends TestCase
{
    public function testGenerateToken()
    {
        $generatedToken = CentrifugeFacade::generateToken(1, 1439311197);
        $this->assertSame('84ec8e8552168d1a17b77ee1a8d9d33ff9215c193bd770715bd859bfce8fa793', $generatedToken);
    }

    public function testGenerateTokenWithInfo()
    {
        $info = json_encode(['info' => true]);
        $generatedToken = CentrifugeFacade::generateToken(2, 1446135665);
        $this->assertSame('7edaa49ca4aec56590e66649cb6e1e180741b6c1dcd5fd49d5efc3115a1ed858', $generatedToken);
    }

    public function testGetConnection()
    {
        $connection = CentrifugeFacade::getConnection();

        $generatedToken = CentrifugeFacade::generateToken('', CentrifugeFacade::getTimestamp());

        $this->assertEquals([
            'url' => 'http://127.0.0.1:8000',
            'user' => '',
            'timestamp' => CentrifugeFacade::getTimestamp(),
            'token' => $generatedToken
        ], $connection);
    }

}