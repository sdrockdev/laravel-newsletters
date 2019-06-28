<?php

namespace Sdrockdev\Newsletters;

use Illuminate\Support\Facades\Facade;

class NewsletterFactoryFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'newsletters';
    }
}
