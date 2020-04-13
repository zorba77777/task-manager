<?php

namespace controllers;

use core\App;
use core\Controller;
use models\Page;
use models\Task;
use models\User;

class MainController extends Controller
{
    public function index()
    {
        $pageNumber = 1;
        $sortedField = '';
        $isReverseSortingOrder = false;

        if (isset(App::$request->requestArray['page'])) {
            $pageNumber = App::$request->requestArray['page'];
        }

        if (isset(App::$request->requestArray['sortfield'])) {
            $sortedField = Page::translateParamFromUrlRequest(App::$request->requestArray['sortfield']);
        }

        if (isset(App::$request->requestArray['sortorder'])) {
            if (App::$request->requestArray['sortorder'] == 'asc') {
                $isReverseSortingOrder = false;
            } elseif ((App::$request->requestArray['sortorder'] == 'desc')) {
                $isReverseSortingOrder = true;
            }
        }

        $page = new Page($pageNumber, 3, $sortedField, $isReverseSortingOrder);

        $user = '';
        if (App::$session->get('login')) {
            $user = new User();
            $user->login = App::$session->get('login');
            $user->findUserByLogin();
        }

        $this->render('index', [
            'page' => $page,
            'user' => $user
        ]);
    }

    public function create()
    {
        $task = new Task();
        $task->userName = App::$request->getParam('user-name');
        $task->email = App::$request->getParam('email');
        $task->content = App::$request->getParam('task-text-new');
        $task->saveRecord();

        setcookie('task_just_created', true, time() + (86400 * 30), "/");

        header("Location: " . App::$request->getReferrer());
    }

    public function done()
    {
        if (App::$session->get('login') == 'admin') {
            $id = App::$request->getParam('id');
            $done = App::$request->getParam('done');

            $task = new Task();
            $task->findRecordByPrimaryKey($id);
            $task->done = $done;
            $task->saveRecord(false);

            echo 'success';
        } else {
            echo 'Admin privileges needed';
        }
    }

    public function edit()
    {
        if (App::$session->get('login') == 'admin') {
            $id = App::$request->getParam('id');
            $content = App::$request->getParam('content');

            $task = new Task();
            $task->findRecordByPrimaryKey($id);
            $task->content = $content;
            $task->editedByAdmin = 1;
            $task->saveRecord(false);

            echo 'success';
        } else {
            echo 'Admin privileges needed';
        }
    }
}