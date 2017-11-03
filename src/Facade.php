<?php

namespace JoelHinz\LaravelQuickSlack;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return QuickSlack::class;
    }
}
