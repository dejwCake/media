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

Router::prefix('admin', ['_namePrefix' => 'admin:'], function ($routes) {
    $routes->plugin(
        'DejwCake/Media',
        ['path' => '/'],
        function (RouteBuilder $routes) {
            $routes->scope('/galleries', function (RouteBuilder $routes) {
                $routes->connect('/', ['controller' => 'Galleries', 'action' => 'index', 'plugin' => 'DejwCake/Media', '_ext' => null]);
                $routes->connect('/add', ['controller' => 'Galleries', 'action' => 'add', 'plugin' => 'DejwCake/Media', '_ext' => null]);
                $routes->connect('/view/:id', ['controller' => 'Galleries', 'action' => 'view', 'plugin' => 'DejwCake/Media', '_ext' => null], ['pass' => ['id'],]);
                $routes->connect('/edit/:id', ['controller' => 'Galleries', 'action' => 'edit', 'plugin' => 'DejwCake/Media', '_ext' => null], ['pass' => ['id'],]);
                $routes->connect('/delete/:id', ['controller' => 'Galleries', 'action' => 'delete', 'plugin' => 'DejwCake/Media', '_ext' => null], ['pass' => ['id'],]);
//                $routes->connect('/enable/:id', ['controller' => 'Galleries', 'action' => 'enable', 'plugin' => 'DejwCake/Media', '_ext' => null], ['pass' => ['id'],]);
                $routes->connect('/sort', ['controller' => 'Galleries', 'action' => 'sort', 'plugin' => 'DejwCake/Media', '_ext' => null]);
            });
        });
});
