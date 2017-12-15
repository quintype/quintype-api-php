<?php

namespace Quintype\Api;
use GuzzleHttp\Exception\RequestException;

class BaseFunctions
{
    public function __construct($client)
    {
        $this->client = $client;
    }

  /** Process the GET request and return the data. **/
  public function getResponse($query = null, $options = [])
  {
      $params = [];
      if (isset($options['authHeader'])) {
          $params['headers'] = [
          'X-QT-AUTH' => $options['authHeader'],
      ];
      }
      if (isset($options['params'])) {
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

  /** Process the POST request and return the data. **/
  public function postRequest($query, $data)
  {
      try {
          $request = $this->client->request('POST', $query, ['json' => $data]);
          $response = json_decode($request->getBody()->getContents(), true);
      } catch (RequestException $e) {
          return false;
      }

      return $response;
  }

  /** Do a POST without Converting to JSON **/
  public function convertBulkBodyToLocation($requests, $cache) {
      $encodedData = json_encode(['requests' => $requests]);

      if($location = $cache['fetch']($encodedData)) {
        return $location;
      };

      $request = $this->client->request('POST', '/api/v1/bulk-request', [
        'body' => $encodedData,
        'headers' => ['Content-Type' => "application/json"],
        'allow_redirects' => false
      ]);

      if($request->getStatusCode() == 303) {
        $location = $request->getHeader("Location")[0];
        $cache['store']($encodedData, $location);
        return $location;
      } else {
        throw new RequestException("Invalid status code in /api/v1/bulk-request: " . $request->getStatusCode());
      }
  }

  /** Used to add any common data required for each element of the payload. **/
  public function buildPayload($requestPayload, $fields)
  {
      $type = 'stories';
      foreach ($requestPayload as $key => $value) {
          $requestPayload[$key]['_type'] = $type;
          if ($fields != '') {
              $requestPayload[$key]['fields'] = $fields;
          }
      }

      return $requestPayload;
  }

  public function removeDateFromSlug($storySlug){
    $slugArray = explode('/', $storySlug);
    if(sizeof($slugArray) === 5){
      return $slugArray[0] . "/" . $slugArray[4];
    } else {
      return $storySlug;
    }
  }

  public function reorderKeys($arr, $order) {
        $output = [];
        foreach($order as $key => $val) {
            $output[$key] = $arr[$key];
        }
        return $output;
    }
}
