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

    /**
     * @param $params
     * @return array['pages', 'authors']
     */
    public function getAuthors($params) {
      $query = '/api/authors';
      return $this->base->getResponse($query, ["params"=> $params]);
    }

    /**
     * @param array $params
     * @return array['pages', 'authors']
     */
    public function getAuthorsV1(array $params)
    {
        $query = '/api/v1/authors';
        return $this->base->getResponse($query, ["params"=> $params]);
    }
}
