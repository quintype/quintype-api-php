<?php

namespace Quintype\Api;

use GuzzleHttp\Client;

class Api
{
    public function __construct($apiHost, $globalSettings = [])
    {
        $this->client = new Client(['base_uri' => $apiHost]);
        $this->config = new Config($this->client, $globalSettings);
        $this->bulk = new Bulk($this->client, $globalSettings);
        $this->stories = new Stories($this->client, $globalSettings);
        $this->author = new Author($this->client, $globalSettings);
        $this->search = new Search($this->client, $globalSettings);
        $this->member = new Member($this->client, $globalSettings);
        $this->storyCollections = new storyCollections($this->client, $globalSettings);
        $this->section = new Section();
        $this->cache = new Cache();
        $this->menu = new Menu();
        $this->breakingNews = new BreakingNews($this->client, $globalSettings);
        $this->contactUs = new ContactUs($this->client, $globalSettings);
        $this->collections = new Collections($this->client, $globalSettings);
        $this->entities = new Entities($this->client);
    }

    public function config()
    {
        return $this->config->config();
    }

    public function addBulkRequest($name, $request, $params = [])
    {
        return $this->bulk->addBulkRequest($name, $request, $params);
    }

    public function executeBulk()
    {
        return $this->bulk->executeBulk();
    }

    public function getBulkResponse($name, $showAltInPage = '')
    {
        return $this->bulk->getBulkResponse($name, $showAltInPage);
    }

    public function buildStacksRequest($stacks, $fields)
    {
        return $this->bulk->buildStacksRequest($stacks, $fields);
    }

    public function buildStacks($stacks)
    {
        return $this->bulk->buildStacks($stacks);
    }

    public function getStoriesByStackName($stackName, $allStacks){
        return $this->bulk->getStoriesByStackName($stackName, $allStacks);
    }

    public function storyBySlug($params)
    {
        return $this->stories->storyBySlug($params);
    }

    public function storyById($story_id)
    {
        return $this->stories->storyById($story_id);
    }

    public function relatedStories($id)
    {
        return $this->stories->relatedStories($id);
    }

    public function storyComments($id)
    {
        return $this->stories->storyComments($id);
    }

    public function stories($params, $showAltInPage = '')
    {
        return $this->stories->stories($params, $showAltInPage);
    }

    public function storyAccessData($id, $sessionCookie)
    {
        return $this->stories->storyAccessData($id, $sessionCookie);
    }

    public function facebookCount($id, $params)
    {
        return $this->stories->facebookCount($params);
    }

    public function getAuthor($id)
    {
        return $this->author->getAuthor($id);
    }

    public function search($search)
    {
        return $this->search->search($search);
    }

    public function searchBase($search)
    {
        return $this->search->searchBase($search);
    }

    public function getCurrentMember($cookie)
    {
        return $this->member->getCurrentMember($cookie);
    }

    public function formLogin($data)
    {
        return $this->member->formLogin($data);
    }

    public function storyCollections($params)
    {
        return $this->storyCollections->storyCollections($params);
    }

    public function latestStoryCollection($params)
    {
        return $this->storyCollections->latestStoryCollection($params);
    }

    public function getSingleIssue($params)
    {
        return $this->storyCollections->getSingleIssue($params);
    }

    public function menuItems($menuItems)
    {
        return $this->menu->menuItems($menuItems);
    }

    public function prepareNestedMenu($menu)
    {
        return $this->menu->prepareNestedMenu($menu);
    }

    public function getSectionDetails($sectionName, $allSections)
    {
        return $this->section->getSectionDetails($sectionName, $allSections);
    }

    public function getKeys($groupKeys, $stories, $publisherId)
    {
        return $this->cache->getKeys($groupKeys, $stories, $publisherId);
    }

    public function getBreakingNews($params = [])
    {
        return $this->breakingNews->getBreakingNews($params);
    }

    // Version 1.6.0 onwards
    public function postContactUs($data) {
        return $this->contactUs->postContact($data);
    }

    //Version 1.7.0 onwards
    public function getCollections($collection, $params) {
        return $this->collections->getCollections($collection, $params);
    }

    //Version 1.7.1 onwards
    public function bulkCollections($requestPayload) {
        return $this->collections->bulkCollections($requestPayload);
    }

    //Version 1.12.0 onwards
    public function getEntity($entityId = '', $params = []) {
        return $this->entities->getEntity($entityId, $params);
    }
}
