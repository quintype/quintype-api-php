<?php

namespace Quintype\Api;

class Collections
{
    public function __construct($client)
    {
        $this->base = new BaseFunctions($client);
    }

    public function getCollections($collection, $params)
    {
        $query = '/api/v1/collections/'.$collection;
        $response = $this->base->getResponse($query, ['params' => $params]);
        if (empty($response)) {
            return false;
        }

        return $response;
    }

    public function bulkCollections($requestPayload)
    {
        $query = '/api/v1/bulk';
        $response = $this->base->postRequest($query, ['requests' => $requestPayload]);

        return $response['results'];
    }
}
