<?php
/** @var Page $page */

/** @var Task $task */

/** @var User $user */

use models\Page;
use models\Task;
use models\User;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Приложение-задачник</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="../../assets/main/css/index.css" rel="stylesheet">

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet"
          type="text/css"/>

    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">

    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">

    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous">
    </script>

    <script type="text/javascript" src="../../assets/main/js/index.js"></script>

</head>
<body>
<div class="container-fluid">

    <div class="row justify-content-center">
        <h1>Приложение-задачник</h1>
    </div>

    <div class="row justify-content-end">
        <?php if ($user): ?>
            <h3>Вы вошли как пользователь <?= $user->login ?></h3>
            <a class="btn btn-secondary" id="logout" href="/auth/logout">Разлогиниться</a>
        <?php else: ?>
            <a class="btn btn-secondary" id="login" href="/auth/authentication">Авторизоваться</a>
        <?php endif ?>
    </div>
    <div class="row justify-content-center"">
        <?php if (($user) && $user->login == 'admin'): ?>
            <p id="hint">Чтобы редактировать текст задачи или ее статус щелкните левой кнопкой мыши по полю текста задачи или по полю ее статуса соответственно. </p>
        <?php endif ?>
    </div>

    <div class="row justify-content-center">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Имя пользователя</th>
                <th>Электронная почта</th>
                <th class="w-25">Текст задачи</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <select class="select-css" id="sort-name">
                        <option
                            <?= ($page->sortedField == '') || ($page->sortedField != 'userName') ? 'selected' : '' ?>>
                            Не сортировать
                        </option>
                        <option
                            <?= ($page->sortedField == 'userName') && (!$page->isReverseOrderOfSorting) ? 'selected' : '' ?>>
                            Сортировать по возрастанию
                        </option>
                        <option <?= ($page->sortedField == 'userName') && ($page->isReverseOrderOfSorting) ? 'selected' : '' ?>>
                            Сортировать по убыванию
                        </option>
                    </select>
                </td>
                <td>
                    <select class="select-css" id="sort-email">
                        <option
                            <?= ($page->sortedField == '') || ($page->sortedField != 'email') ? 'selected' : '' ?>>
                            Не сортировать
                        </option>
                        <option
                            <?= ($page->sortedField == 'email') && (!$page->isReverseOrderOfSorting) ? 'selected' : '' ?>>
                            Сортировать по возрастанию
                        </option>
                        <option <?= ($page->sortedField == 'email') && ($page->isReverseOrderOfSorting) ? 'selected' : '' ?>>
                            Сортировать по убыванию
                        </option>
                    </select>
                </td>
                <td>
                    <select class="select-css" id="sort-content">
                        <option
                            <?= ($page->sortedField == '') || ($page->sortedField != 'content') ? 'selected' : '' ?>>
                            Не сортировать
                        </option>
                        <option
                            <?= ($page->sortedField == 'content') && (!$page->isReverseOrderOfSorting) ? 'selected' : '' ?>>
                            Сортировать по возрастанию
                        </option>
                        <option <?= ($page->sortedField == 'content') && ($page->isReverseOrderOfSorting) ? 'selected' : '' ?>>
                            Сортировать по убыванию
                        </option>
                    </select>
                </td>
                <td>
                    <select class="select-css" id="sort-status">
                        <option
                            <?= ($page->sortedField == '') || ($page->sortedField != 'done') ? 'selected' : '' ?>>
                            Не сортировать
                        </option>
                        <option
                            <?= ($page->sortedField == 'done') && (!$page->isReverseOrderOfSorting) ? 'selected' : '' ?>>
                            Сортировать по возрастанию
                        </option>
                        <option <?= ($page->sortedField == 'done') && ($page->isReverseOrderOfSorting) ? 'selected' : '' ?>>
                            Сортировать по убыванию
                        </option>
                    </select>
                </td>
            </tr>

            <?php foreach ($page->tasks as $task): ?>
                <tr>
                    <td><?= $task->userName ?></td>
                    <td><?= $task->email ?></td>
                    <td class="task-content <?= $task->editedByAdmin ? 'edited' : '' ?>"
                        id="content-<?= $task->getPrimaryKeyValue() ?>"><?= $task->content ?></td>
                    <td <?= ($user) && ($user->login == 'admin') ? 'class="task-done"' : '' ?>
                        id="done-<?= $task->getPrimaryKeyValue() ?>"><?= $task->done ? 'Выполнено' : 'Не выполнено' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row justify-content-center before-bottom">
        <button type="button" data-toggle="modal" data-target="#create-task">Создать задачу</button>
    </div>

    <div class="modal fade" id="create-task" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Новая задача</h5>
                </div>
                <div class="modal-body">

                    <form action="/main/create" method="post">
                        <div class="form-group">
                            <label for="user-name" class="col-form-label">имя пользователя</label>
                            <input type="text" class="form-control" id="user-name" name="user-name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">e-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="task-text-new" class="col-form-label">текст задачи</label>
                            <textarea class="form-control form-control-lg" id="task-text-new"
                                      name="task-text-new" required></textarea>
                        </div>
                        <div class="form-inline">
                            <input id="save-task-new" type="submit" value="Сохранить задачу">
                            <button type="button" data-dismiss="modal">Закрыть</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <?php if ($user && ($user->login == 'admin')): ?>
    <div class="modal fade" id="edit-task" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать задачу</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="task-text-edit" class="col-form-label">текст задачи</label>
                            <textarea class="form-control form-control-lg" id="task-text-edit"
                                      name="task-text-edit" required autofocus></textarea>
                        </div>
                        <div class="form-inline">
                            <button id="save-task-edit" type="submit" data-dismiss="modal">Сохранить правки</button>
                            <button type="button" data-dismiss="modal">Закрыть</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <footer class="page-footer fixed-bottom row">

        <?php if (($page->pageNumber != 1) && ($page->totalPagesCount > 1)): ?>
            <a class="btn btn-secondary" href="/main/index<?= $page->getUrlRequestParamsToPage(1) ?>"><<< На первую
                страницу</a>
            <a class="btn btn-secondary"
               href="/main/index<?= $page->getUrlRequestParamsToPage($page->pageNumber - 1) ?>">< Предыдущая
                страница</a>
        <?php else: ?>
            <a class="btn disabled" href="#"><<< На первую страницу</a>
            <a class="btn disabled" href="#">< Предыдущая страница</a>
        <?php endif; ?>

        <span class="font-size-lg"><?= $page->pageNumber ?></span>

        <?php if (($page->totalPagesCount != 0) && ($page->pageNumber < $page->totalPagesCount)): ?>
            <a class="btn btn-secondary"
               href="/main/index<?= $page->getUrlRequestParamsToPage($page->pageNumber + 1) ?>">Следующая страница ></a>
            <a class="btn btn-secondary"
               href="/main/index<?= $page->getUrlRequestParamsToPage($page->totalPagesCount) ?>">На последнюю страницу
                >>></a>
        <?php else: ?>
            <a class="btn disabled" href="#">Следующая страница</a>
            <a class="btn disabled" href="#">На последнюю страницу</a>
        <?php endif; ?>

    </footer>


</body>
</html>