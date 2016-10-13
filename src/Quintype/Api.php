<?php

namespace Quintype\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

class Api
{
  public function __construct($apiHost){
    $this->client = new Client(['base_uri' => $apiHost]);
    $this->config = new Config($this->client);
    $this->bulk = new Bulk($this->client);
    $this->stories = new Stories($this->client);
    $this->author = new Author($this->client);
    $this->search = new Search($this->client);
    $this->member = new Member($this->client);
    $this->storyCollections = new storyCollections($this->client);
  }

  public function config(){
    return $this->config->config();
  }

  public function addBulkRequest($name, $request, $params = []){
    return $this->bulk->addBulkRequest($name, $request, $params);
  }

  public function executeBulk() {
    return $this->bulk->executeBulk();
  }

  public function getBulkResponse($name) {
    return $this->bulk->getBulkResponse($name);
  }

  public function buildStacksRequest($stacks, $fields){
    return $this->bulk->buildStacksRequest($stacks, $fields);
  }

  public function buildStacks($stacks){
    return $this->bulk->buildStacks($stacks);
  }

  public function storyBySlug($params){
    return $this->stories->storyBySlug($params);
  }

  public function storyById($story_id){
    return $this->stories->storyById($story_id);
  }

  public function relatedStories($id){
    return $this->stories->relatedStories($id);
  }

  public function storyComments($id){
    return $this->stories->storyComments($id);
  }

  public function stories($params){
    return $this->stories->stories($params);
  }

  public function storyAccessData($id, $sessionCookie) {
    return $this->stories->storyAccessData($id, $sessionCookie);
  }

  public function facebookCount($id, $params){
    return $this->stories->facebookCount($params);
  }

  public function getAuthor($id){
    return $this->author->getAuthor($id);
  }

  public function search($search){
    return $this->search->search($search);
  }

  public function getCurrentMember($cookie) {
    return $this->member->getCurrentMember($cookie);
  }

  public function formLogin($data) {
    return $this->member->formLogin($data);
  }

  public function storyCollections($params) {
    return $this->storyCollections->storyCollections($params);
  }

  public function latestStoryCollection($params) {
    return $this->storyCollections->latestStoryCollection($params);
  }

  public function getSingleIssue($params){
    return $this->storyCollections->getSingleIssue($params);
  }

  public function menuItems($menuItems) {
    return array_map(function($menu) {
      return new MenuItem($menu, $this);
    }, $menuItems);
  }
}
