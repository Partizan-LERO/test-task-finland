<?php

namespace Framework\Exceptions;

use Exception;

class UnknownDatabaseDriverException extends Exception {
    public $message = 'Unknown Database Driver Exception';
}
