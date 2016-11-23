<?php

namespace Quintype\Api;

class Config
{
    public function __construct($client)
    {
        $this->base = new BaseFunctions($client);
    }

    public function config()
    {
        $query = '/api/v1/config';
        $response = $this->base->getResponse($query);

        return $response;
    }
}
