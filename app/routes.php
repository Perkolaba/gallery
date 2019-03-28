<?php

use App\Services\QueryBuilder;
use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use Intervention\Image\ImageManager;
use League\Plates\Engine;

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    Engine::class => function() {
    return new Engine('../app/views');
    },
    PDO::class => function() {
    return new PDO('mysql:host=localhost; dbname=gallery','root', '');
    },
    QueryFactory::class => function() {
    return new QueryFactory('mysql');
    },
    QueryBuilder::class => function($container) {
    $pdo = $container->get(PDO::class);
    $queryFactory = $container->get(QueryFactory::class);
    return new QueryBuilder($pdo, $queryFactory);
    },
    Auth::class => function($container) {
    return new Auth($container->get(PDO::class));
    },
    ImageManager::class => function() {
    return new ImageManager(array('driver' => 'imagick'));
    }

]);

$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    $r->addRoute('GET', '/category', ['App\Controllers\HomeController', 'category']);
    $r->addRoute('GET', '/category/{id:\d+}', ['App\Controllers\HomeController', 'category']);

    $r->addRoute('GET', '/email-verification', ['App\Controllers\VerificationController', 'showForm']);
    $r->addRoute('GET', '/login', ['App\Controllers\LoginController', 'showForm']);
    $r->addRoute('POST', '/login', ['App\Controllers\LoginController', 'login']);
    $r->addRoute('GET', '/logout', ['App\Controllers\LoginController', 'logout']);
    $r->addRoute('GET', '/password-reset', ['App\Controllers\PasswordResetController', 'showForm']);

    $r->addRoute('GET', '/photo/{id:\d+}', ['App\Controllers\PhotoController', 'show']);
    $r->addRoute('GET', '/photos', ['App\Controllers\PhotoController', 'showMyPhotos']);
    $r->addRoute('GET', '/photos/upload', ['App\Controllers\PhotoController', 'showUploadForm']);
    $r->addRoute('POST', '/photos/upload', ['App\Controllers\PhotoController', 'upload']);

    $r->addRoute('GET', '/profile-info', ['App\Controllers\ProfileController', 'showInfo']);
    $r->addRoute('POST', '/profile-info', ['App\Controllers\ProfileController', 'updateInfo']);
    $r->addRoute('GET', '/profile-security', ['App\Controllers\ProfileController', 'showSecurity']);
    $r->addRoute('POST', '/profile-security', ['App\Controllers\ProfileController', 'updateSecurity']);
    $r->addRoute('GET', '/register', ['App\Controllers\RegisterController', 'showForm']);
    $r->addRoute('POST', '/register', ['App\Controllers\RegisterController', 'registerUser']);

    $r->addRoute('GET', '/set-new-password', ['App\Controllers\PasswordResetController', 'showNewPasswordForm']);



});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo pageNotFound();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 | Method is wrong';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $container->call($handler, $vars);
        // ... call $handler with $vars
        break;
}