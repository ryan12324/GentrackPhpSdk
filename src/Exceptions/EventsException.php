<?php
/**
 * Created by PhpStorm.
 * User: Ryan Johnson
 * Date: 05/04/2018
 * Time: 21:47
 */

namespace ryan12324\GentrackPhpSdk\Exceptions;


use Throwable;
use Exception;

class EventsException extends Exception
{
    public $signature;

    public function __construct($message = "", $signature = "", $code = 0, Throwable $previous = null) {
        $this->signature = $signature;

        parent::__construct($message, $code, $previous);
    }

}