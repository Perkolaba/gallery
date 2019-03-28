<?php

use App\Services\QueryBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;


function auth()
{
    global $container;
    return $container->get(Auth::class);
}

function redirect($path) {

    header("Location: $path");
    exit;

}

function pageNotFound()
{
    global $container;
    return $container->get(Engine::class)->render('errors/404');

}

function getAllCategories()
{
    global $container;
    $categories = $container->get(QueryBuilder::class)->all('category');
    return $categories;
}