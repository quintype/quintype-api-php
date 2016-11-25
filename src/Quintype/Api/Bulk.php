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

    public function executeBulk()
    {
        $requests = [];
        foreach ($this->requests as $key => $value) {
            $requests[$key] = $value->toBulkRequest();
        }
        $apiResponse = $this->getStories($requests);
        $responses = [];
        foreach ($this->requests as $key => $value) {
            $responses[$key] = $value->fromBulkResponse($apiResponse[$key]);
        }
        $this->responses = $responses;
    }

    public function getBulkResponse($name, $showAltInPage = '')
    {
        if ($showAltInPage === '') {
            return $this->responses[$name];
        } else {
            return $this->alternativeForBulk($this->responses[$name], $showAltInPage);
        }
    }

    private function getStories($requestPayload)
    {
        $query = '/api/v1/bulk';
        $response = $this->base->postRequest($query, ['requests' => $requestPayload]);

        return $response['results'];
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
                        $story['hero-image-metadata'] = $default['hero-image']['hero-image-metadata'];
                        $story['hero-image-s3-key'] = $default['hero-image']['hero-image-s3-key'];
                        $story['hero-image-caption'] = $default['hero-image']['hero-image-caption'];
                        $story['hero-image-attribution'] = $default['hero-image']['hero-image-attribution'];
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
