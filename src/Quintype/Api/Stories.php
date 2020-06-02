<?php

namespace Quintype\Api;

class Stories
{
    public function __construct($client, $globalSettings)
    {
        $this->base = new BaseFunctions($client);
        $this->removeDateFromSlugs = isset($globalSettings['removeDateFromSlugs']) && $globalSettings['removeDateFromSlugs'];
    }

    public function storyBySlug($params)
    {
        $query = '/api/v1/stories-by-slug';
        $response = $this->base->getResponse($query, ['params' => $params]);
        if($this->removeDateFromSlugs){
          $response['story']['slug'] = $this->base->removeDateFromSlug($response['story']['slug']);
        }

        return $response['story'];
    }

    public function storyById($story_id)
    {
        $query = '/api/v1/stories/'.$story_id;
        $response = $this->base->getResponse($query);
        if($this->removeDateFromSlugs){
          $response['story']['slug'] = $this->base->removeDateFromSlug($response['story']['slug']);
        }

        return $response;
    }

    public function relatedStories($id, $params = [])
    {
        $query = '/api/v1/stories/'.$id.'/'.'related-stories';

        $response = $this->base->getResponse($query, ['params' => $params]);

        return $response['related-stories'];
    }

    public function storyComments($id)
    {
        $query = '/api/v1/stories/'.$id.'/'.'comments';
        $response = $this->base->getResponse($query);

        return $response['comments'];
    }

    public function stories($params, $showAltInPage = '')
    {
        $query = '/api/v1/stories';
        $response = $this->base->getResponse($query, ['params' => $params]);
        if (empty($response)) {
            return false;
        }
        if($this->removeDateFromSlugs){
          foreach ($response['stories'] as $key => $story) {
            $response['stories'][$key]['slug'] = $this->base->removeDateFromSlug($response['stories'][$key]['slug']);
          }
        }

        if ($showAltInPage === '') {
            return $response['stories'];
        } else {
            return $this->alternativeForStories($response['stories'], $showAltInPage);
        }
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

    public function engagementCount($id, $params)
    {
        $query = '/api/stories/'.$id.'/engagement';
        $response = $this->base->getResponse($query, ['params' => $params]);

        return $response;
    }

    public function alternativeForStories($stories, $alternativePage)
    {
        foreach ($stories as $key => $story) {
            if (isset($story['alternative']) && sizeof($story['alternative']) > 0) {
                $default = $story['alternative'][$alternativePage]['default'];
                if (isset($default)) {
                    if (isset($default['headline'])) {
                        $stories[$key]['headline'] = $default['headline'];
                    }
                    if (isset($default['hero-image'])) {
                        $stories[$key]['hero-image-metadata'] = $default['hero-image']['hero-image-metadata'];
                        $stories[$key]['hero-image-s3-key'] = $default['hero-image']['hero-image-s3-key'];
                        $stories[$key]['hero-image-caption'] = $default['hero-image']['hero-image-caption'];
                        $stories[$key]['hero-image-attribution'] = $default['hero-image']['hero-image-attribution'];
                    }
                }
            }
        }

        return $stories;
    }

    public function publicPreview($encryptedKey)
    {
        $query = '/api/v1/preview/story/'.$encryptedKey;
        $response = $this->base->getResponse($query);

        return $response['story'];
    }

    public function ampStory($storySlug){
        $query = 'api/v1/amp/story?slug='.$storySlug;
        $response = $this->base->getResponse($query);

        return $response;
    }
}
