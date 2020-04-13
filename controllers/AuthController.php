<?php


namespace controllers;


use core\App;
use core\Controller;
use models\User;

class AuthController extends Controller
{
    public function registration()
    {
        if (App::$request->getParam('login')) {

            $login = App::$request->getParam('login');
            $password = App::$request->getParam('password');
            $email = App::$request->getParam('email');

            $password = password_hash($password, PASSWORD_DEFAULT);

            $user = new User(0, $login, $password, $email);

            if (!$user->hasLoginAlreadyExist()) {
                $user->saveRecord();

                App::$session->set('login', $login);
                echo 'Регистрация прошла успешно. Через 3 секунды вы будете перенаправлены на главную страницу сайта.';

            } else {
                echo 'При регистрации произошла ошибка. Логин, который вы пытались использовать, уже существует';
            }
            header('Refresh: 3; URL=http://' . App::$request->getServerName() . '/main/index');
            exit;
        }

        $this->render('registration');
    }

    public function authentication() {
        if (App::$request->getParam('login') && App::$request->getParam('password')) {
            $login = App::$request->getParam('login');
            $password = App::$request->getParam('password');

            $user = new User(0, $login, $password, '');

            if ($user->isPasswordCorrect()) {
                App::$session->set('login', App::$request->getParam('login'));
                echo 'Авторизация прошла успешно. Через 3 секунды вы будете перенаправлены на главную страницу сайта.';
                header('Refresh: 3; URL=http://' . App::$request->getServerName() . '/main/index');
                exit;
            } else {
                echo 'При при авторизации произошла ошибка. Логин и/или пароль, которые вы использовали, неверны';
            }
        }

        $this->render('auth');
    }

    public function checklogin() {
        $login = App::$request->getParam('login');

        $user = new User();
        $user->login = $login;

        if (!$user->hasLoginAlreadyExist()) {
            echo 'login does not exist';
        } else {
            echo 'login exist';
        }
    }

    public function checkpassword() {
        $login = App::$request->getParam('login');
        $password = App::$request->getParam('password');

        $user = new User();
        $user->login = $login;
        $user->password = $password;

        if ($user->isPasswordCorrect()) {
            echo 'correct';
        } else {
            echo 'incorrect';
        }
    }

    public function logout() {
        App::$session->set('login', '');
        header("Location: " . App::$request->getReferrer());
    }
}