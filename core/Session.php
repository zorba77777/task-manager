<?php

namespace core;

class Session
{
    private array $session;

    public function __construct()
    {
        session_start();
        $this->session = $_SESSION;
        session_write_close();
    }

    public function set($nameVariable, $value)
    {
        session_start();
        $_SESSION[$nameVariable] = $value;
        session_write_close();
        $this->session[$nameVariable] = $value;
    }

    public function get($nameVariable)
    {
        if (isset($this->session[$nameVariable])) {
            $value = $this->session[$nameVariable];
        } else {
            $value = null;
        }
        return $value;
    }

}