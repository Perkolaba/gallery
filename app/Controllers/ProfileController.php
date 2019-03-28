<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 14:14
 */

namespace App\Controllers;


use App\Services\ProfileService;
use App\Services\QueryBuilder;
use App\Services\RegistrationService;
use League\Plates\Engine;

class ProfileController extends Controller
{
    private $registration;
    private $profile;

    public function __construct(Engine $engine, RegistrationService $registration, ProfileService $profile, QueryBuilder $queryBuilder)
    {
        parent::__construct($engine, $queryBuilder);
        $this->registration = $registration;
        $this->profile = $profile;
    }

    public function showInfo()
    {

        echo $this->view->render('profile/profile-info');

    }

    public function showSecurity()
    {

        echo $this->view->render('profile/profile-security');

    }

    public function updateSecurity()
    {

        $this->profile->updateSecurity();

    }
    public function updateInfo()
    {

        $this->profile->updateInfo();

    }

}