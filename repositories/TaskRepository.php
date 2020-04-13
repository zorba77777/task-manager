<?php


namespace repositories;


use core\ActiveRecord;
use core\Repository;
use models\Task;

class TaskRepository extends Repository
{
    protected ActiveRecord $model;

    public function __construct()
    {
        $this->model = new Task();
    }

}