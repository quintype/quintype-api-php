<?php

namespace Quintype\Api;

class Member
{
  public function __construct($client){
    $this->base = new BaseFunctions($client);
  }

  public function getCurrentMember($cookie) {
    $query = "/api/v1/members/me";
    return $this->base->getResponse($query, ['authHeader'=>$cookie]);
  }

  public function formLogin($data) {
    $query = "/api/member/login";
    $response = $this->base->postRequest($query, $data);
    if ($response && $response->getHeader('Set-Cookie')) {
      $cookie = Psr7\parse_header($response->getHeader('Set-Cookie'))[0]['session-cookie'];
      return urldecode($cookie);
    }
  }
}
