<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 16:20
 */

namespace App\Controllers;


use App\Services\QueryBuilder;
use League\Plates\Engine;

class PasswordResetController extends Controller
{
    public function __construct(Engine $engine, QueryBuilder $queryBuilder)
    {
        parent::__construct($engine, $queryBuilder);
    }

    public function showForm()
    {

        echo $this->view->render('auth/password-reset');

    }

    public function showNewPasswordForm()
    {

        echo $this->view->render('auth/set-new-password');

    }


}