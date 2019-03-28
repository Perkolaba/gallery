<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 14:15
 */

namespace App\Controllers;


use App\Services\QueryBuilder;
use League\Plates\Engine;

class VerificationController extends Controller
{

    public function __construct(Engine $engine, QueryBuilder $queryBuilder)
    {
        parent::__construct($engine, $queryBuilder);
    }

    public function showForm()
    {

        echo $this->view->render('auth/email-verification');

    }

}