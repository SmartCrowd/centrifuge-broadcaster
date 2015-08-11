<?php

namespace SmartCrowd\Centrifuge;

use Illuminate\Support\Facades\Facade;


/**
 * Class CentrifugeFacade
 * @package SmartCrowd\Centrifuge
 */
class CentrifugeFacade extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'centrifugeManager';
    }
}