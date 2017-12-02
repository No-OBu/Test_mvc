<?php

return [
  'login_route' => [
    'path' => '/login',
    'controller' => 'ChatController',
    'action' => 'loginAction',
  ],
  'logout_route' => [
    'path' => '/logout',
    'controller' => 'ChatController',
    'action' => 'logoutAction',
  ],
  'chat_route' => [
    'path' => '/chat',
    'controller' => 'ChatController',
    'action' => 'chatAction',
  ],
  'home_route' => [
    'path' => '/',
    'controller' => 'ChatController',
    'action' => 'homeAction',
  ],
];
