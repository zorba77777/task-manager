<?php

namespace core;

interface IdentityInterface
{
    public function isPasswordCorrect();

    public function hasLoginAlreadyExist();

    public function isGuest();

}