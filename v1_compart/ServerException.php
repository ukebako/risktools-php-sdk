<?php namespace Risktools;

class ServerException extends \Exception
{
    /**
     * Server api endpoint origin (system, client, order, etc)
     */
    public $origin;

    public function __construct($message, $code, $origin)
    {
        $this->origin = $origin;
        parent::__construct($message, $code);
    }
}
