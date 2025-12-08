<?php

require __DIR__ . '/../vendor/autoload.php';

use Laravel\Vercel\Vercel;

$app = require_once __DIR__ . '/../bootstrap/app.php';

Vercel::handler($app);
