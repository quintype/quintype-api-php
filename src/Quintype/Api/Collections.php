<?php

namespace Quintype\Api;

class Collections
{
    public function __construct($client, $globalSettings)
    {
        $this->base = new BaseFunctions($client);
        $this->removeDateFromSlugs = isset($globalSettings['removeDateFromSlugs']) && $globalSettings['removeDateFromSlugs'];
        $this->keyMapData = [];
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

    private function mapNewKeys($requestPayload)
    {
        $updatedMap = [];
        $count = 1;
        foreach ($requestPayload as $key => $payload) {
            $updatedMap["col{$count}"] = $requestPayload[$key];
            $this->keyMapData["col{$count}"] = $key;
            ++$count;
        }

        return $updatedMap;
    }

    private function mapOriginalKeys($collectionResponse)
    {
        $mapWithOriginalKeys = [];
        foreach ($collectionResponse as $key => $response) {
            $mapWithOriginalKeys[$this->keyMapData[$key]] = $response;
        }

        $this->keyMapData = [];
        return $mapWithOriginalKeys;
    }

    public function bulkCollectionsCached($requestPayload) {
        $requestPayload = $this->mapNewKeys($requestPayload);
        $response = $this->base->postRequest('/api/v1/bulk-request', ['requests' => $requestPayload]);
        $collectionResponse = $this->base->reorderKeys($response['results'], $requestPayload);
        return $this->mapOriginalKeys($collectionResponse);
    }
}
