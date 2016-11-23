<?php

namespace Quintype\Api;

class Cache
{
    public function getKeys($groupKeys, $stories, $publisherId)
    {
        $keys = [];
        foreach ($groupKeys as $x) {
            array_push($keys, "q/$publisherId/$x");
        }
        foreach ($stories as $x) {
            array_push($keys, "s/$publisherId/".preg_replace('/-.*/', '', $x['id']));
        }

        return implode(' ', $keys);
    }
}
