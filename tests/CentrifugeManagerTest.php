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
        $generatedToken = CentrifugeFacade::generateToken(2, 1446135665, $info);
        $this->assertSame('833ec1c64ceafb7060a7c38d46afb143ec5bc38506e198baf1c19f04190f10da', $generatedToken);
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