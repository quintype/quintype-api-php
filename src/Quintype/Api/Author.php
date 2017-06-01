<?php

namespace Quintype\Api;

class Author
{
    public function __construct($client)
    {
        $this->base = new BaseFunctions($client);
    }

    public function getAuthor($id)
    {
        $query = '/api/v1/authors/'.$id;
        $response = $this->base->getResponse($query);

        return $response['author'];
    }
    
    public function getAuthors($params) {
      $query = '/api/authors';
      return $this->base->getResponse($query, ["params"=> $params]);
    }
}
