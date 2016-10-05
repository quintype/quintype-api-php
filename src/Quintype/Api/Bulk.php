<?php

namespace Quintype\Api;

use ArrayObject;

class Bulk
{
  public function __construct($client) {
    $this->requests = [];
    $this->base = new BaseFunctions($client);
  }

  public function addRequest($name, $request) {
    $this->requests[$name] = $request;
    return $this;
  }

  public function execute($client) {
    $requests = [];
    foreach($this->requests as $key => $value) {
      $requests[$key] = $value->toBulkRequest();
    }
    $apiResponse = $this->getStories($requests);
    $responses = [];
    foreach($this->requests as $key => $value) {
      $responses[$key] = $value->fromBulkResponse($apiResponse[$key]);
    }
    $this->responses = $responses;
  }

  public function getResponse($name) {
    return $this->responses[$name];
  }

  private function getStories($requestPayload, $fields = ''){
    $query = '/api/v1/bulk';
    $payload = $this->base->buildPayload($requestPayload, $fields);//Add necessary data that are missing in the payload.
    $response = $this->base->postRequest($query, ["requests" => $payload]);//Get the stories.
    return $response['results'];
  }
}

class Story extends ArrayObject
{

}

class StoriesRequest
{
  public function __construct($storyGroup) {
    $this->params = ["story-group" => $storyGroup, "_type" => "stories"];
  }

  public function addParams($params) {
    $this->params = array_merge($this->params, $params);
    return $this;
  }

  public function toBulkRequest() {
    return $this->params;
  }

  public function fromBulkResponse($response) {
    return array_map(function ($s) {
      return new Story($s);
    }, $response["stories"]);
  }
}
