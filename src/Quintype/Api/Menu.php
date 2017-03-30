<?php

namespace Quintype\Api;

use ArrayObject;

class Menu
{
    public function menuItems($menuItems)
    {
        return array_map(function ($menu) {
            return new MenuItem($menu, $this);
        }, $menuItems);
    }

    public function prepareNestedMenu($menu)
    {
        /* Key the array by id */
        $keyedMenu = array();
        foreach ($menu as &$value) {
            $keyedMenu[$value['id']] = &$value;
        }
        unset($value);
        $keyedMenuArray = $keyedMenu;
        unset($keyedMenu);

        /* Tree it */
        $tree = array();
        foreach ($keyedMenuArray as &$value) {
            if ($parent = $value['parent-id']) {
                $keyedMenuArray[$parent]['children'][] = &$value;
            } else {
                $tree[] = &$value;
            }
        }
        unset($value);
        $keyedMenuArray = $tree;
        unset($tree);

        return $keyedMenuArray;
    }
}

class MenuItem extends ArrayObject
{
    public function __construct($hash, $config)
    {
        parent::__construct($hash);
    }

    public function title()
    {
        return $this['title'];
    }

    //Use slug() instead of url(). Function url() is kept for backward compatibility.
    public function url()
    {
        try {
            switch ($this['item-type']) {
              case 'section': return $this['section-slug'];
              case 'link': return $this['data']['link'];
              case 'tag': return '/tag?tag='.$this['tag-name'];
              default: return '#';
            }
        } catch (Exception $e) {
            return '#';
        }
    }

    public function slug()
    {
        try {
            switch ($this['item-type']) {
              case 'section': return $this['section-slug'];
              case 'link': return $this['data']['link'];
              case 'tag': return $this['tag-name'];
              default: return '#';
            }
        } catch (Exception $e) {
            return '#';
        }
    }

    public function menuType()
    {
        return $this['item-type'];
    }
}
