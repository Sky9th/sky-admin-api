<?php

use think\facade\Env;

return [
    'client_id' => Env::get('database.client_id', ''),
    'client_secret' => Env::get('database.client_secret', ''),
];