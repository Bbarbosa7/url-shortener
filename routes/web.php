<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return json_encode([
        'description' => 'Welcome to Arco URL Shortener.',
        'version' => '1.0'
    ], JSON_THROW_ON_ERROR);
});

$router->post('[{url:.*}]', 'UrlController@create');

$router->get('/{slug}', 'UrlController@show');
