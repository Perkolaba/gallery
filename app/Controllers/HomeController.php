<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 13:02
 */

namespace App\Controllers;


use App\Services\QueryBuilder;
use League\Plates\Engine;

class HomeController extends Controller
{

    public function __construct(Engine $engine, QueryBuilder $queryBuilder)
    {
        parent::__construct($engine, $queryBuilder);

    }

    public function index()
    {

        echo $this->view->render('index');

    }

    public function category($id) //id
    {

        $category = $this->queryBuilder->find('category', $id);
        echo $this->view->render('category', ['category' => $category]);

    }

}