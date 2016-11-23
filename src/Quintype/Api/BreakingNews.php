<?php

namespace Quintype\Api;

class BreakingNews
{
    public function __construct($client)
    {
        $this->base = new BaseFunctions($client);
    }

    public function getBreakingNews($params)
    {
        $query = '/api/v1/breaking-news';
        $response = $this->base->getResponse($query, ['params' => $params]);

        return $response['stories'];
    }
}
