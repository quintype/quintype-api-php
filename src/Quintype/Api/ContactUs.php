<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Quintype\Api;

class ContactUs
{
    public function __construct($client)
    {
        $this->base = new BaseFunctions($client);
    }


    public function postContact($data)
    {
        $endPoint = '/api/emails/contact';
        $response = $this->base->postRequest($endPoint, $data);
        return $response;
    }
}
