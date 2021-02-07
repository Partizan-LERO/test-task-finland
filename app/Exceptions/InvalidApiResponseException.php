<?php
namespace App\Exceptions;

class InvalidApiResponseException extends \Exception
{
    public $message = 'Invalid API response body';
}
