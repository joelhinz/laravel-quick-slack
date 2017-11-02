<?php

namespace JoelHinz\LaravelQuickSlack;

use Illuminate\Support\Facades\Facade;

class LaravelQuickSlackFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LaravelQuickSlack::class;
    }
}
