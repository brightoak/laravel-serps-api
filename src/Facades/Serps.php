<?php

namespace BrightOak\Serps\Facades;

use Illuminate\Support\Facades\Facade;

class Serps extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'laravel-serps-api';
    }

}