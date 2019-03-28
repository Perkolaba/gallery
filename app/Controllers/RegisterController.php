<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 16:41
 */

namespace App\Controllers;


use App\Services\QueryBuilder;
use App\Services\RegistrationService;
use League\Plates\Engine;

class RegisterController extends Controller
{

    private $registration;

    public function __construct(Engine $engine, RegistrationService $registration, QueryBuilder $queryBuilder)
    {
        parent::__construct($engine, $queryBuilder);
        $this->registration = $registration;
    }

    public function showForm()
    {

        echo $this->view->render('auth/register');

    }

    public function registerUser()

    {

        $this->registration->register();

    }

}