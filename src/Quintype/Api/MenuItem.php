<?php

namespace Quintype\Api;
use ArrayObject;

class MenuItem extends ArrayObject
{
  public function __construct($hash, $config){
    parent::__construct($hash);
  }

  public function title() {
    return $this["title"];
  }

  public function url() {
    try {
      switch ($this["item-type"]) {
        case "section": return "/section/" . $this["section-slug"];
        case "link": return $this["data"]["link"];
        case "tag": return "/tag/" . $this["tag-name"];
        default: return "#";
      }
    } catch (Exception $e) {
      return "#";
    }
  }
}
