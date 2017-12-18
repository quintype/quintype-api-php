<?php

namespace Quintype\Api;

class Tags
{
    public function __construct($client)
    {
        $this->base = new BaseFunctions($client);
    }

    public function getTagsBySlug($tagSlug)
    {
      $query = '/api/v1/tags/'.$tagSlug;
      $response = $this->base->getResponse($query, []);

      return $response;
    }
}
