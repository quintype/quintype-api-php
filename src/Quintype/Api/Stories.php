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

    public function prepareAlternateDetails($stories, $alternativePage)
    {
        foreach ($stories as $story) {
            if (sizeof($story['alternative']) > 0) {
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
