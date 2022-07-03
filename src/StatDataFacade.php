<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Facade;

class StatDataFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'StatData';
    }
}