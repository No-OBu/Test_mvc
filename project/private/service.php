<?php

use Controllers\ChatController;
use Application\Session;
use Application\Router;
use Application\DbConnection;

return [
  'router' => [
    'class' => Router::class,
    'parameters' => [
      '$route'
    ]
  ],
  'session' => [
    'class' => Session::class,
    'parameters' => [
    ]
  ],
  'db' => [
    'class' => DbConnection::class,
    'parameters' => [
      '$dbName',
      '$host',
      '$user',
      '$password',
    ]
  ],
  'BaseController' => [
    'class' => ChatController::class,
    'parameters' => [
      '$env',
      '#router',
      '#session',
      '#db',
      '$server'
    ]
  ],
  'ChatController' => [
    'class' => ChatController::class,
    'parameters' => [
      '$env',
      '#router',
      '#session',
      '#db',
      '$server'
    ]
  ],
];
