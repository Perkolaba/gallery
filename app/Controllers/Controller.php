<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 14:17
 */

namespace App\Controllers;


use App\Services\QueryBuilder;
use League\Plates\Engine;

class Controller
{
    protected $view;
    protected $queryBuilder;

    public function __construct(Engine $engine, QueryBuilder $queryBuilder)
    {

        $this->view = $engine;
        $this->queryBuilder = $queryBuilder;
    }

}