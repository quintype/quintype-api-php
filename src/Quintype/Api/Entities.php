<?php

namespace Quintype\Api;

class Entities
{
    public function __construct($client)
    {
        $this->base = new BaseFunctions($client);
    }

    public function getEntity($entityId)
    {
        $query = '/api/v1/entity/'.$entityId;
        $response = $this->base->getResponse($query);


        return $response;
    }
}
