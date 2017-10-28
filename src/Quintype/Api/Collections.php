<?php

namespace Quintype\Api;

class Collections
{
    public function __construct($client, $globalSettings)
    {
        $this->base = new BaseFunctions($client);
        $this->removeDateFromSlugs = isset($globalSettings['removeDateFromSlugs']) && $globalSettings['removeDateFromSlugs'];
    }

    public function getCollections($collection, $params)
    {
        $query = '/api/v1/collections/'.$collection;
        $response = $this->base->getResponse($query, ['params' => $params]);
        if (empty($response)) {
            return false;
        }
        if ($this->removeDateFromSlugs){
          foreach ($response['items'] as $key => $item) {
            if ($item['type'] === 'story') {
              $response['items'][$key]['story']['slug'] = $this->base->removeDateFromSlug($response['items'][$key]['story']['slug']);
            }
          }
        }

        return $response;
    }

    public function bulkCollections($requestPayload)
    {
        $query = '/api/v1/bulk';
        $response = $this->base->postRequest($query, ['requests' => $requestPayload]);
        if ($this->removeDateFromSlugs){
          foreach ($response['results'] as $bulkKey => $collections) {
            foreach ($collections['items'] as $itemKey => $item) {
              if ($item['type'] === 'story') {
                $response['results'][$bulkKey]['items'][$itemKey]['story']['slug'] = $this->base->removeDateFromSlug($response['results'][$bulkKey]['items'][$itemKey]['story']['slug']);
              }
            }
          }
        }

        return $response['results'];
    }

    public function bulkCollectionsCached($requestPayload) {
        $response = $this->base->postRequest('/api/v1/bulk-request', ['requests' => $requestPayload]);
        return $this->base->reorderKeys($response['results'], $requestPayload);
    }
}
