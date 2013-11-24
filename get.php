<?php

require 'Cache.php';

Cache::setup('./cache/');
echo 'data: ' . Cache::get('key');
