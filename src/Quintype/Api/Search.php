<?php

namespace Quintype\Api;

class Search
{
    public function __construct($client, $globalSettings)
    {
        $this->base = new BaseFunctions($client);
        $this->removeDateFromSlugs = isset($globalSettings['removeDateFromSlugs']) && $globalSettings['removeDateFromSlugs'];
    }

    public function searchBase($search = null)
    {
        $query = '/api/v1/search?';
        $first = true;
        foreach ($search as $key => $value) {
            $string = '&'.$key.'='.urlencode($value);
            if ($first) {
                $string = $key.'='.urlencode($value);
            }
            $query .= $string;
            $first = false;
        }
        $response = $this->base->getResponse($query);
        if($this->removeDateFromSlugs){
          foreach ($response['results']['stories'] as $key => $story) {
            $response['results']['stories'][$key]['slug'] = $this->base->removeDateFromSlug($response['results']['stories'][$key]['slug']);
          }
        }

        return $response['results'];
    }

    public function search($search = null)
    {
        return $this->searchBase($search)['stories'];
    }
}
