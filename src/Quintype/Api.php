<?php

namespace Quintype\Api;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Client;

function add_header($header, $value)
{
    return function (callable $handler) use ($header, $value) {
        return function (
            RequestInterface $request,
            array $options
        ) use ($handler, $header, $value) {
            $request = $request->withHeader($header, $value);
            return $handler($request, $options);
        };
    };
}

class Api
{
    public function __construct($apiHost, $globalSettings = [])
    {
        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(add_header('Accept-Encoding', 'gzip'));
        $this->client = new Client([
            'base_uri' => $apiHost,
            'handler' => $stack
        ]);

        $this->config = new Config($this->client, $globalSettings);
        $this->bulk = new Bulk($this->client, $globalSettings, $apiHost);
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
        $this->tags = new Tags($this->client);
        $this->redirectPath = new RedirectPath($this->client);
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

    public function executeBulkCached()
    {
        return $this->bulk->executeBulkCached();
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

    public function relatedStories($id, $params = [])
    {
        return $this->stories->relatedStories($id, $params);
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
        return $this->stories->engagementCount($id, $params);
    }

    public function getAuthor($id)
    {
        return $this->author->getAuthor($id);
    }

    //Version 1.13.0 onwards
    public function getAuthors($params = [])
    {
        return $this->author->getAuthors($params);
    }

    public function getAuthorsV1($params = [])
    {
        return $this->author->getAuthorsV1($params);
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
    public function getCollections($collection, $params = []) {
        return $this->collections->getCollections($collection, $params);
    }

    //Version 1.7.1 onwards
    public function bulkCollections($requestPayload) {
        return $this->collections->bulkCollections($requestPayload);
    }

    public function bulkCollectionsCached($requestPayload)
    {
        return $this->collections->bulkCollectionsCached($requestPayload);
    }

    //Version 1.12.0 onwards
    public function getEntity($entityId = '', $params = []) {
        return $this->entities->getEntity($entityId, $params);
    }

    //Version 1.11.1 onwards
    public function engagementCount($id, $params = ["fields" => "shrubbery,facebook"])
    {
        return $this->stories->engagementCount($id, $params);
    }

    public function publicPreview($encryptedKey) {
        return $this->stories->publicPreview($encryptedKey);
    }

    public function getTagsBySlug($tagSlug = '') {
        return $this->tags->getTagsBySlug($tagSlug);
    }

    public function getRedirectPath($url = '') {
        return $this->redirectPath->getRedirectPath($url);
    }

    public function getAmpStory($storySlug){
        return  $this->stories->ampStory($storySlug);
    }

}
