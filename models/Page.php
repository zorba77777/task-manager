<?php


namespace models;


use core\App;
use core\SqlRequestParams;
use repositories\TaskRepository;

class Page
{
    public int $pageNumber;

    public int $totalPagesCount;

    public int $tasksOnPageCount;

    public string $sortedField;

    public bool $isReverseOrderOfSorting;

    public string $totalTasks;

    public array $tasks;

    public function __construct($pageNumber = 1, $tasksOnPage = 3, $sortedField = '', $isReverseOrderOfSorting = false)
    {
        $this->pageNumber = $pageNumber;
        $this->tasksOnPageCount = $tasksOnPage;
        $this->sortedField = $sortedField;
        $this->isReverseOrderOfSorting = $isReverseOrderOfSorting;
        $this->totalTasks = self::getTotalTasks();
        $this->totalPagesCount = $this->getTotalPagesCount();
        $this->setTasks();
    }

    public static function getTotalTasks()
    {
        $query = "SELECT COUNT(*) FROM tasks";
        $searchResult = App::$pdo->query($query)->fetch();
        return $searchResult['COUNT(*)'];
    }

    public function getTotalPagesCount()
    {
        return ceil($this->totalTasks / $this->tasksOnPageCount);
    }

    public function setTasks()
    {
        $limit = $this->tasksOnPageCount;
        $offset = ($this->pageNumber - 1) * $this->tasksOnPageCount;

        $requestParams = new SqlRequestParams();
        $requestParams->limit = $limit;
        $requestParams->offset = $offset;
        $requestParams->orderBy = $this->sortedField;

        if ($this->isReverseOrderOfSorting) {
            $requestParams->orderBy = $requestParams->orderBy . ' DESC';
        }

        $taskRepository = new TaskRepository();
        $this->tasks = $taskRepository->find($requestParams);
    }

    public static function translateParamFromUrlRequest($sortField) {
        switch ($sortField) {
            case 'name':
                return 'userName';
            case 'email':
                return 'email';
            case 'content':
                return 'content';
            case 'status':
                return 'done';
            default:
                return '';
        }
    }

    public static function translateParamsToUrlRequest($sortField) {
        switch ($sortField) {

            case 'userName':
                return 'name';
            case 'email':
                return 'email';
            case 'content':
                return 'content';
            case 'done':
                return 'status';
            default:
                return '';
        }
    }

    public function getUrlRequestParamsToPage($pageNumber) {

        $requestParams = '?page=' . $pageNumber;

        if ($this->sortedField) {
            $requestParams = $requestParams . '&sortfield=' . self::translateParamsToUrlRequest($this->sortedField);
            $requestParams = $requestParams . '&sortorder=';

            if ($this->isReverseOrderOfSorting) {
                $requestParams = $requestParams . 'desc';
            } else {
                $requestParams = $requestParams . 'asc';
            }
        }

        return $requestParams;
    }

}