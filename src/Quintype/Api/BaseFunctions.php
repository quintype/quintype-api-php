<?php

namespace Quintype\Api;

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
          $request = $this->client->request('POST', $query, [
          'json' => $data,
      ]);
          $response = json_decode($request->getBody()->getContents(), true);
      } catch (RequestException $e) {
          return false;
      }

      return $response;
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
