<?php

namespace Quintype\Api;

class RedirectPath
{
    public function __construct($client, $globalSettings = [])
    {
        $this->base = new BaseFunctions($client);
    }
    public function getRedirectPath($url)
    {
      //Sometimes one might append '/' in the beginning while calling the function. Sometimes they wont.
      //But the API expects it to be there. So, to handle this, first strip if it is there, and then append anyway.
      $url = urlencode("/" . ltrim($url, "/"));

      $query = '/api/v1/custom-urls/'. $url;
      $response = $this->base->getResponse($query);
      return $response;
    }
}
