<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.04.2018
 * Time: 14:38
 */

namespace App\Services;


use Delight\Auth\Auth;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class ProfileService
{

    public $url;
    private $auth;

    public function __construct(Auth $auth)
    {

        $this->auth = $auth;

    }

    public function updateSecurity()
    {

        $this->validateNewPass();

        try {
            $this->auth->changePassword($_POST['password'], $_POST['new_password']);
            flash()->success('Пароль успешно изменен!');

        }
        catch (\Delight\Auth\NotLoggedInException $e) {

            flash()->error(['Вы не залогинены']);
            // not logged in
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {

            flash()->error('Вы ввели неправильный пароль');

            // invalid password(s)
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {

            flash()->error(['Слишком много попыток, попробуйте позже']);
            // too many requests

        }

        return redirect('profile-security');

    }

    public function updateInfo()
    {

        $this->validateNewUserInfo();
        try {
            if ($this->auth->reconfirmPassword($_POST['password']))
                $this->auth->changeEmail($_POST['email'], function ($selector, $token) {
                    // send `$selector` and `$token` to the user (e.g. via email to the *new* address)
                    $this->url = 'https://www.gallery.com/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);
                    flash()->success('Ваш профиль обновлен');
                });

                // the change will take effect as soon as the new email address has been confirmed

            else {
                // we can't say if the user is who they claim to be
            }
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            // invalid email address
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            // email address already exists
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // account not verified
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            // not logged in
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
        }

        return redirect('profile-info');

    }

    private function validateNewPass()
    {
        $validator = v::keyValue('password_confirmation', 'equals', 'new_password');

        try {
            $validator->assert($_POST);
        }
        catch (ValidationException $exception) {
            $exception->findMessages($this->getMessages());
            flash()->error($exception->getMessages());

            return redirect('profile-security');
        }
    }

    public function validateNewUserInfo()
    {

        $validator = v::key('username', v::stringType()->notEmpty())
                      ->key('email', v::email());

        try {
            $validator->assert($_POST);
        }
        catch (ValidationException $exception) {
            $exception->findMessages($this->getMessages());
            flash()->error($exception->getMessages());

            return redirect('profile-info');
        }

    }

    private function getMessages()
    {
        return [
            'password_confirmation' =>  'Пароли не сопадают',
            'password' => 'Вы ввели неверный пароль',
            'email' => 'Пользователь с таким email уже существет',
            'username' => 'Пользователь с таким именем уже существует'
        ];
    }
}