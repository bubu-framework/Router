<?php

namespace Bubu\Router\Exception;

use Bubu\Http\Reponse\Reponse;
use Exception;

class RouterException extends \Exception
{
    public function __construct(string $msg, int $code)
    {
        (new Reponse)->setHttpCode($code)->setHttpMessage($msg)->setup()->send();
        throw new Exception($msg, $code);
    }
}
