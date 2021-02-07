<?php

namespace Framework\Exceptions;

use Exception;

class UnknownCacheDriverException extends Exception {

    public $message = 'Unknown Cache Driver Exception';
}
