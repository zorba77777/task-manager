<?php


namespace models;

use core\ActiveRecord;

class Task extends ActiveRecord
{
    public static string $tableName = 'tasks';

    public int $taskId;

    public string $userName;

    public string $email;

    public string $content;

    public int $done;

    public int $editedByAdmin;

    public function __construct($taskId = 0, $userName = '', $email = '', $content = '', $done = 0, $editedByAdmin = 0)
    {
        $this->taskId = $taskId;
        $this->userName = $userName;
        $this->email = $email;
        $this->content = $content;
        $this->done = $done;
        $this->editedByAdmin = $editedByAdmin;
    }

    public function getPrimaryKeyName(): string
    {
        return 'taskId';
    }

    public function getPrimaryKeyValue()
    {
        return $this->taskId;
    }
}