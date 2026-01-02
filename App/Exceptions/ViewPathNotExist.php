<?php

declare(strict_types=1);

namespace App\Exceptions;

class ViewPathNotExist extends \Exception
{
    protected $message = 'Viewpath not found, try again';
}