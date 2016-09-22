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
        $query = '/api/v1/config';
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
    private function buildPayload($requestPayload, $fields){
        $type = 'stories';
        foreach ($requestPayload as $key => $value) {
            $requestPayload[$key]['_type']=$type;
            if($fields!=''){
                $requestPayload[$key]['fields']=$fields;
            }
        }

        return $requestPayload;
    }

    /**
    Function to be called for fetching all the stories in different categories.
    **/
    public function getStories($requestPayload, $fields = ''){
        $query = '/api/v1/bulk';
        $payload = $this->buildPayload($requestPayload, $fields);//Add necessary data that are missing in the payload.
        $response = $this->postRequest($query, ["requests" => $payload]);//Get the stories.

        return $response['results'];
    }

    /**
    Function to be called for fetching a single story.
    **/
    public function story($params){
        $query = '/api/v1/stories-by-slug';
        $response = $this->getResponse($query, ['params' => $params]);

        return $response['story'];
    }

    /**
    Function to be called for fetching all the stories of a single story group.
    **/
    public function stories($params){
        $query = '/api/v1/stories';
        $response = $this->getResponse($query, ['params' => $params]);

        if (empty($response)) {
            return false;
        }

        return $response['stories'];
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
        $query = '/api/v1/authors/' . $id;
        $response = $this->getResponse($query);

        return $response['author'];
    }

    public function latestStoryCollection($params) {
        $query = '/api/story-collection/find-by-tag';
        $response = $this->getResponse($query, ['params' => $params]);

        if (empty($response)) {
            return false;
        }

        return $response;
    }

    /**
    Make a search.
    **/
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

        $response = $this->getResponse($query);
        return $response["results"]['stories'];
    }

    public function storyAccessData($id, $sessionCookie) {
        $query = "/api/v1/stories/" . $id . "/access-data";
        if ($sessionCookie) {
            return $this->getResponse($query, ['authHeader'=>$sessionCookie]);
        }
    }

    /**
    All and weekly issues.
    **/
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

    public function menu(){
        $query = '/api/v1/config';
        $response = $this->getResponse($query);

        return $response;
    }

    public function getCurrentMember($cookie) {
        $query = "/api/v1/members/me";

        return $this->getResponse($query, ['authHeader'=>$cookie]);
    }

    /**
    Called when trying to log in a user.
    **/
    public function formLogin($data) {
        $query = "/api/member/login";
        $response = $this->postRequest($query, $data);
        if ($response && $response->getHeader('Set-Cookie')) {
            $cookie = Psr7\parse_header($response->getHeader('Set-Cookie'))[0]['session-cookie'];
            return urldecode($cookie);
        }
    }

    /**
    For related stories and comments in TGD.
    **/
    public function storyRelated($id, $relation=''){
        $query = "/api/v1/stories/" . $id . "/" . $relation;
        $response = $this->getResponse($query)[$relation];

        return $response;
    }

    public function storyDataById($story_id){
        $query = "/api/v1/stories/" . $story_id;
        $response = $this->getResponse($query);

        return $response;
    }

}







