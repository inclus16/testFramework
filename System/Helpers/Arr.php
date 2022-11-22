<?php

namespace System\Helpers;

class Arr
{
    public static function first(iterable $array, callable $callback): mixed
    {
        foreach ($array as $item) {
            if ($callback($item)) {
                return $item;
            }
        }
        return null;
    }
}