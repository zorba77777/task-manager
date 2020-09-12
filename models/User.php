<?php

namespace models;

use core\ActiveRecord;
use core\App;
use core\SqlRequestParams;

class User extends ActiveRecord
{
    public static string $tableName = 'users';

    public int $userId;

    public string $login;

    public string $password;

    public string $email;


    public function __construct($userId = 0, $login = '', $password = '', $email = '')
    {
        $this->userId = $userId;
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
    }

    public function getPrimaryKeyName(): string
    {
        return "userId";
    }

    public function getPrimaryKeyValue()
    {
        return $this->userId;
    }

    public function isPasswordCorrect()
    {
        $bufferLogin = $this->login;
        $bufferPassword = $this->password;

        $sqlParams = new SqlRequestParams();
        $sqlParams->where = "login = '{$this->login}'";

        $this->findRecord($sqlParams);

        if (($this->login == $bufferLogin) && (password_verify($bufferPassword, $this->password))) {
            return true;
        } else {
            return false;
        }

    }

    public function hasLoginAlreadyExist()
    {
        $sqlParams = new SqlRequestParams();
        $sqlParams->where = "login = '{$this->login}'";

        if ($this->findRecord($sqlParams)) {
            return true;
        } else {
            return false;
        }
    }

    public function isGuest()
    {
        return empty(App::$session->get('login'));
    }

    public function findUserByLogin() {
        $sqlParams = new SqlRequestParams();
        $sqlParams->where = "login = '{$this->login}'";
        $this->findRecord($sqlParams);
    }

}