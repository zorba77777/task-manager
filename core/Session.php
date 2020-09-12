<?php

namespace core;

/**
 * Класс для работы с сессией
 * Class Session
 * @package core
 */
class Session
{
    /**
     * Поле хранит массив сессии
     * @var array
     */
    private array $session;

    /**
     * В конструкторе происходит получение данных из сессии.
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
        $this->session = $_SESSION;
        session_write_close();
    }

    /**
     * Функция служит для записи данных в сессию
     * @param $nameVariable
     * @param $value
     */
    public function set($nameVariable, $value)
    {
        session_start();
        $_SESSION[$nameVariable] = $value;
        session_write_close();
        $this->session[$nameVariable] = $value;
    }


    /**
     * Функция служит для получения данных из сессии
     * @param $nameVariable
     * @return mixed|null
     */
    public function get($nameVariable): ?string
    {
        if (isset($this->session[$nameVariable])) {
            $value = $this->session[$nameVariable];
        } else {
            $value = null;
        }
        return $value;
    }

}