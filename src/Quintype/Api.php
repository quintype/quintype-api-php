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
    $this->section = new Section();
    $this->cache = new Cache();
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

  public function getSectionDetails($sectionName, $allSections){
    return $this->section->getSectionDetails($sectionName, $allSections);
  }

  public function getKeys($groupKeys, $stories, $publisherId){
    return $this->cache->getKeys($groupKeys, $stories, $publisherId);
  }

  public function levelTwoMenuItems($menuItems) {
    $childIndices = [];
    foreach ($menuItems as $key => $menuItem) {
      if(!isset($menuItems[$key]['sub-menus'])){
        $menuItems[$key]['sub-menus'] = [];
      }
      if(!is_null($menuItem['parent-id']) && !empty($menuItem['parent-id'])){
        $parentIndex = array_search($menuItem['parent-id'], array_column($menuItems, "id"), true);
        if($menuItem['item-type'] == 'section'){
          $menuItem['section-slug'] = $menuItems[$parentIndex]['section-slug']. "/" . $menuItem['section-slug'];
        }
        array_push($menuItems[$parentIndex]['sub-menus'], $menuItem);
        $childIndex = array_search($menuItem['id'], array_column($menuItems, "id"), true);
        array_push($childIndices, $childIndex);
      }
    }
    foreach ($childIndices as $key => $value) {
      unset($menuItems[$value]);
    }
    return $this->menuItems($menuItems);
  }

}
