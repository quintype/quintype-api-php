<?php

namespace Quintype\Api;

use GuzzleHttp\Client;

class AccessType
{
    public function __construct($apiHost = '', $accessToken = '')
    {
        $this->accessType = new Client(['base_uri' => $apiHost]);
        $this->accessToken = $accessToken;
    }

    private function getResponse($query = null, $options = [])
    {
        $params = [];
        if ($this->accessToken) {
          $params['headers'] = [
            'X-SUBAUTH' => $this->accessToken,
          ];
        }
        if (isset($options['params'])) {
          $params['query'] = $options['params'];
        }

        try {
            $request = $this->accessType->request('GET', $query, $params);
            $response = json_decode($request->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return false;
        }

        return $response;
    }

    public function getAllSubscriptionGroups()
    {
      $query = '/api/v1/subscription_groups.json';
      $response = $this->getResponse($query, []);

      return $response;
    }

}
