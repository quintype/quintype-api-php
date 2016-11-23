<?php

namespace Quintype\Api;

class Stories
{
    public function __construct($client)
    {
        $this->base = new BaseFunctions($client);
    }

    public function storyBySlug($params)
    {
        $query = '/api/v1/stories-by-slug';
        $response = $this->base->getResponse($query, ['params' => $params]);

        return $response['story'];
    }

    public function storyById($story_id)
    {
        $query = '/api/v1/stories/'.$story_id;
        $response = $this->base->getResponse($query);

        return $response;
    }

    public function relatedStories($id)
    {
        $query = '/api/v1/stories/'.$id.'/'.'related-stories';
        $response = $this->base->getResponse($query);

        return $response['related-stories'];
    }

    public function storyComments($id)
    {
        $query = '/api/v1/stories/'.$id.'/'.'comments';
        $response = $this->base->getResponse($query);

        return $response['comments'];
    }

    public function stories($params)
    {
        $query = '/api/v1/stories';
        $response = $this->base->getResponse($query, ['params' => $params]);
        if (empty($response)) {
            return false;
        }

        return $response['stories'];
    }

    public function storyAccessData($id, $sessionCookie)
    {
        $query = '/api/v1/stories/'.$id.'/access-data';
        if ($sessionCookie) {
            return $this->base->getResponse($query, ['authHeader' => $sessionCookie]);
        }
    }

    public function facebookCount($id, $params)
    {
        $query = '/api/stories/'.$id.'/engagement';
        $response = $this->base->getResponse($query, ['params' => $params]);

        return $response;
    }
}
