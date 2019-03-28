<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 17:36
 */

namespace App\Services;


use Delight\Auth\Auth;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class RegistrationService
{

    private $pdo;
    private $auth;
    public $url;

    public function __construct(\PDO $pdo, Auth $auth)
    {

        $this->pdo = $pdo;
        $this->auth = $auth;

    }

    public function register()
    {

        $this->validate();

        try {
            $this->auth->registerWithUniqueUsername($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                // send `$selector` and `$token` to the user (e.g. via email)
                $this->url = 'https://www.gallery.com/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);
            });
            redirect('/');

            // we have signed up a new user with the ID `$userId`
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            // user already exists
            flash()->error('Пользователь уже существует!');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
            flash()->error('Слишком много попыток!');
        } catch (\Delight\Auth\DuplicateUsernameException $e) {
            // DuplicateUsernameException
            flash()->error('Пользователь с данным именем уже существует!');
        }

        return redirect('register');

    }

    public function login()
    {

        try {
            $this->auth->login($_POST['email'], $_POST['password']);
            return redirect('/');

            // user is logged in
        } catch (\Delight\Auth\InvalidEmailException $e) {
            // wrong email address
            flash()->error('Неверный email!');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            // wrong password
            flash()->error('Неверный пароль!');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // email not verified
            flash()->error('email не подтверджен!');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
            flash()->error('слишком много попыток, попробуйте позже!');
        }

        return redirect('login');

    }

    public function logout()
    {

        try {
            $this->auth->logOutEverywhere();
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            flash()->message('not logged in');
        }

    }

    //create rules
    private function validate() {
        $validator = v::key('username', v::stringType()->notEmpty())
            ->key('email', v::email())
            ->key('password', v::stringType()->notEmpty())
            ->key('terms', v::trueVal())
            ->keyValue('password_confirmation', 'equals', 'password');

        try {
            $validator->assert($_POST);
        }
        catch (ValidationException $exception) {
            $exception->findMessages($this->getMessages());
            flash()->error($exception->getMessages());

            return redirect('register');
        }
    }

    private function getMessages()
    {
        return [
            'terms'   =>  'Вы должны согласится с правилами',
            'username' => 'Введите имя',
            'email' => 'Неверный формат e-mail',
            'password'  =>  'Введите пароль',
            'password_confirmation' =>  'Пароли не сопадают'
        ];
    }

}