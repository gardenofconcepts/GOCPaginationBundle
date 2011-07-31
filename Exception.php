<?php

namespace GOC\PaginationBundle;

class Exception extends \Exception
{
    public static function unknownPageNumber()
    {
        return new self('Can\'t determine requested page number from request object');
    }
}
