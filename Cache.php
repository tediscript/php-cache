<?php

/**
 * Description of Cache
 *
 * @author tediscript
 */
class Cache
{

    private static $filePath = './';

    public static function setup($filePath = '')
    {
        Cache::$filePath = $filePath;
    }

    public static function show()
    {
        return Cache::$filePath;
    }

    public static function put($key = '', $value = '', $minutes = 0)
    {
        $obj = new stdClass();
        if ($minutes === 1) {
            $obj->exp = @strtotime('+' . $minutes . ' minute');
        } else if ($minutes > 1) {
            $obj->exp = @strtotime('+' . $minutes . ' minutes');
        } else {
            $obj->exp = null;
        }
        $obj->data = $value;

        $path = Cache::getPath($key);
        $content = serialize($obj);
        file_put_contents($path, $content);
    }

    public static function forever($key = '', $value = '')
    {
        Cache::put($key, $value, 0);
    }

    public static function has($key = '')
    {
        $obj = Cache::getObject($key);
        if ($obj) {
            return true;
        } else {
            Cache::forget($key);
            return false;
        }
    }

    public static function get($key = '')
    {
        $obj = Cache::getObject($key);
        if ($obj) {
            return $obj;
        } else {
            Cache::forget($key);
            return false;
        }
    }

    public static function add($key = '', $value = '', $minutes = 0)
    {
        if (!Cache::has($key)) {
            Cache::put($key, $value, $minutes);
        }
    }

    public static function forget($key = '')
    {
        $path = Cache::getPath($key);
        @unlink($path);
    }

    private static function getPath($key = '')
    {
        return Cache::$filePath . md5($key);
    }

    private static function getObject($key = '')
    {
        $path = Cache::getPath($key);
        $str = @file_get_contents($path);
        if ($str) {
            $obj = unserialize($str);
            $now = @strtotime('now');
            if ($obj->exp === null || $obj->exp > $now) {
                return $obj->data;
            } else {
                return false;
            }
        }
    }

}

