<?php

namespace Quintype\Api;

class storyCollections
{
  public function __construct($client){
    $this->base = new BaseFunctions($client);
  }

  public function storyCollections($params) {
    $query = '/api/v1/story-collections/content';
    $response = $this->base->getResponse($query, ['params' => $params]);
    if (empty($response)) {
      return false;
    }
    return $response['story-collections'];
  }

  public function latestStoryCollection($params) {
    $query = '/api/story-collection/find-by-tag';
    $response = $this->base->getResponse($query, ['params' => $params]);
    if (empty($response)) {
      return false;
    }
    return $response;
  }

  public function getSingleIssue($params){
    $query = '/api/story-collection';
    $issue = $this->base->getResponse($query, ['params' => $params]);
    return $issue;
  }
}
