<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Scanner extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \G4T\IDScanner\Scanner::class;
    }
}
