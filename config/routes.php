<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::prefix('media', ['_namePrefix' => 'media:'], function ($routes) {
    $routes->plugin(
        'DejwCake/Media',
        ['path' => '/'],
        function (RouteBuilder $routes) {
            $routes->scope('/upload', function (RouteBuilder $routes) {
                $routes->connect('/', ['controller' => 'Uploader', 'action' => 'upload', 'plugin' => 'DejwCake/Media']);
            });
        });
});
