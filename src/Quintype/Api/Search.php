<?php

namespace Quintype\Api;

class Search
{
  public function __construct($client){
    $this->base = new BaseFunctions($client);
  }

  public function search($search = null){
    $query = '/api/v1/search?';
    $first = true;
    foreach ($search as $key => $value) {
      $string = '&' . $key . '=' . urlencode($value);
      if ($first) {
          $string = $key . '=' . urlencode($value);
      }
      $query .= $string;
      $first = false;
    }
    $response = $this->base->getResponse($query);
    return $response["results"]['stories'];
  }
}
