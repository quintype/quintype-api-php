<?php

namespace Quintype\Api;

use ArrayObject;

class Bulk
{
    public function __construct($client)
    {
        $this->requests = [];
        $this->base = new BaseFunctions($client);
    }

    public function addBulkRequest($name, $request, $params)
    {
        $this->requests[$name] = (new StoriesRequest($request))->addParams($params);

        return $this;
    }

    private function doExecuteBulk($callback)
    {
        $requests = [];
        foreach ($this->requests as $key => $value) {
            $requests[$key] = $value->toBulkRequest();
        }
        $apiResponse = $callback($requests);
        $responses = [];
        foreach ($this->requests as $key => $value) {
            $responses[$key] = $value->fromBulkResponse($apiResponse[$key]);
        }
        $this->responses = $responses;
    }

    public function executeBulk()
    {
        return $this->doExecuteBulk(function ($requests) {
            $response = $this->base->postRequest('/api/v1/bulk', ['requests' => $requestPayload]);
            return $response['results'];
        });
    }

    public function executeBulkCached()
    {
        return $this->doExecuteBulk(function ($requests) {
            $location = $this->base->convertBulkBodyToLocation($requests, [
                'fetch' => function($x) { return \Cache::get($x); },
                'store' => function($x, $y) { return \Cache::forever($x, $y); }
            ]);
            $response = $this->base->getResponse($location);
            return $response['results'];
        });
    }

    public function getBulkResponse($name, $showAltInPage = '')
    {
        if ($showAltInPage === '') {
            return $this->responses[$name];
        } else {
            return $this->alternativeForBulk($this->responses[$name], $showAltInPage);
        }
    }

    public function buildStacksRequest($stacks, $fields)
    {
        foreach ($stacks as $stack) {
            $this->addBulkRequest(trim($stack['heading']), $stack['story-group'], ['limit' => $stack['max-stories'], 'fields' => $fields]);
        }

        return $this;
    }

    public function buildStacks($stacks)
    {
        $stacksArray = [];
        foreach ($stacks as $stack) {
            $stories = $this->getBulkResponse(trim($stack['heading']));
            array_push($stacksArray, ['heading' => trim($stack['heading']), 'stories' => $stories]);
        }

        return $stacksArray;
    }

    public function getStoriesByStackName($stackName, $allStacks)
    {
        $stackIndex = array_search($stackName, array_column($allStacks, 'heading'), true);
        if ($stackIndex !== false) {
            return $allStacks[$stackIndex];
        } else {
            return array();
        }
    }

    private function alternativeForBulk($stories, $alternativePage)
    {
        foreach ($stories as $story) {
            if (isset($story['alternative']) && sizeof($story['alternative']) > 0) {
                $default = $story['alternative'][$alternativePage]['default'];
                if (isset($default)) {
                    if (isset($default['headline'])) {
                        $story['headline'] = $default['headline'];
                    }
                    if (isset($default['hero-image'])) {
                        $heroImage = $default['hero-image'];
                        $story['hero-image-metadata'] = isset($heroImage['hero-image-metadata'])?$heroImage['hero-image-metadata']:'';
                        $story['hero-image-s3-key'] = isset($heroImage['hero-image-s3-key'])?$heroImage['hero-image-s3-key']:'';
                        $story['hero-image-caption'] = isset($heroImage['hero-image-caption'])?$heroImage['hero-image-caption']:'';
                        $story['hero-image-attribution'] = isset($heroImage['hero-image-attribution'])?$heroImage['hero-image-attribution']:'';
                    }
                }
            }
        }

        return $stories;
    }
}

class Story extends ArrayObject
{
}

class StoriesRequest
{
    public function __construct($storyGroup)
    {
        $this->params = ['story-group' => $storyGroup, '_type' => 'stories'];
    }

    public function addParams($params)
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    public function toBulkRequest()
    {
        return $this->params;
    }

    public function fromBulkResponse($response)
    {
        return array_map(function ($s) {
            return new Story($s);
        }, $response['stories']);
    }
}
