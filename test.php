<?php

require 'Cache.php';

Cache::setup('./cache/');
Cache::put('key', 'value', 1);
echo 'data: ' . Cache::get('key') . '<br/>';
Cache::forget('key');
echo 'data: ' . Cache::get('key') . '<br/>';
