<?php

namespace Quintype\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

class Api
{
    public function __construct($apiHost){
        $this->client = new Client([
            'base_uri' => $apiHost,
        ]);
    }

    /**
    Get the entire configuration for the app.
    **/
    public function config(){
        $query = '/api/config';
        $response = $this->getResponse($query);

        return $response;
    }

    /**
    Process the GET request and return the data.
    **/
    private function getResponse($query = null, $options = []){
        $params = [];
        if(isset($options['authHeader'])){
            $params['headers'] = [
                'X-QT-AUTH' => $options['authHeader']
            ];
        }
        if(isset($options['params'])){
            $params['query'] = $options['params'];
        }

        try {
            $request = $this->client->request('GET', $query, $params);
            $response = json_decode($request->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return false;
        }

        return $response;
    }

    /**
    Process the POST request and return the data.
    **/
    private function postRequest($query, $data){
        try {
            $request = $this->client->request('POST', $query, [
                'json' => $data
            ]);
            $response = json_decode($request->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return false;
        }

        return $response;
    }

    /**
    Used to add any common data required for each element of the payload.
    **/
    private function buildPayload($requestPayload){
        $type = 'stories';
        foreach ($requestPayload as $key => $value) {
            $requestPayload[$key]['_type']=$type;
        }

        return $requestPayload;
    }

    /**
    Function to be called for fetching all the stories.
    **/
    public function getStories($requestPayload){
        $query = '/api/bulk';
        $payload = $this->buildPayload($requestPayload);//Add necessary data that are missing in the payload.
        $response = $this->postRequest($query, ["requests" => $payload]);//Get the stories.

        return $response['results'];
    }

    /**
    Function to be called for fetching the a single story.
    **/
    public function story($params){
        $query = '/api/stories-by-slug';
        $response = $this->getResponse($query, ['params' => $params]);

        return $response;
    }

    /**
    Get the facebook share count for the story.
    **/
    public function facebookCount($id, $params){
        $query = '/api/stories/' . $id . '/engagement';
        $response = $this->getResponse($query, ['params' => $params]);

        return $response;
    }

    /**
    Get the author details of a story.
    **/
    public function getAuthor($id){
        $query = '/api/author/' . $id;
        $response = $this->getResponse($query);

        return $response;
    }

    public function stories($params){
        $query = '/api/stories';

        $response = $this->getResponse($query, ['params' => $params]);

        if (empty($response)) {
            return false;
        }

        return $response;
    }

    public function latestStoryCollection($params) {
        $query = '/api/story-collection/find-by-tag';
        $response = $this->getResponse($query, ['params' => $params]);

        if (empty($response)) {
            return false;
        }

        return $response;
    }

    public function search($search = null){
        $query = '/api/search?';
        $first = true;
        foreach ($search as $key => $value) {
            $string = '&' . $key . '=' . urlencode($value);
            if ($first) {
                $string = $key . '=' . urlencode($value);
            }

            $query .= $string;
            $first = false;
        }

        return $this->getResponse($query);
    }

    public function storyAccessData($id, $sessionCookie) {
        $query = "/api/v1/stories/" . $id . "/access-data";
        if ($sessionCookie) {
            return $this->getResponse($query, ['authHeader'=>$sessionCookie]);
        }
    }

    public function storyCollections($params) {
        $query = '/api/v1/story-collections/content';

        $response = $this->getResponse($query, ['params' => $params]);

        if (empty($response)) {
            return false;
        }

        return $response['story-collections'];
    }

    public function getSingleIssue($params){
        $query = '/api/story-collection';
        $issue = $this->getResponse($query, ['params' => $params]);

        return $issue;
    }


}







