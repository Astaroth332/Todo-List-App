<?php

declare(strict_types=1);

namespace App\Controller;

use App\Models\TaskModel;
use App\View;
use Exception;
class TaskController
{


    public function addNewTask()
    {
        return View::make('Task/addNewTask');
    }

    public function storeTask()
    {
        $title = $_POST['title'];
        $dueDate = $_POST['dueDate'];
        $level = (int) $_POST['importantLevel'];

        $taskModel = new TaskModel();

        $taskModel->addTaskToDB([
            'title' => $title,
            'dueDate' => $dueDate,
            'level' => $level,
        ]);

        header('Location: /MVC/srcForTodoList/Public/index.php/');
        exit;
    }

    public function toggleComplete()
    {
    $completedIds = $_POST['completed'] ?? [];

    $_SESSION['selectedIDsOfCompletedTasks']  = $completedIds;

    $keyword = $_SESSION['currentSearch'];
 
    $taskModel = new TaskModel();
    $taskModel->updateTaskStatus($completedIds);

    $redirect ='/MVC/srcForTodoList/Public/index.php/';

    if(!empty($keyword))
    {
        $redirect .= '?search=' . urlencode( $keyword);
    }

    unset($_SESSION['currentSearch']);

    header('Location: ' . $redirect);
    exit;
    }

    public function migrateFinishedTask()
    {
        $fromTableName = 'tasks';
        $toTableName = 'finishedtasks';
        $selectedIDS  =  $_SESSION['selectedIDsOfCompletedTasks'] ?? [];
        $taskModel = new TaskModel();
        $taskModel->addFinishedTaskToArchive($selectedIDS, $fromTableName, $toTableName);

        header('Location: /MVC/srcForTodoList/Public/index.php/'); // 
        exit;
    }


    public function toggleInComplete()
    {
    $unCompletedIds = $_POST['uncompleted'] ?? [];

    $_SESSION['selectedIDsOfUncompletedTasks']  = $unCompletedIds;
 
    
    $taskModel = new TaskModel();
    $taskModel->updateTaskStatusFromArchive($unCompletedIds);

    header('Location: /MVC/srcForTodoList/Public/index.php/displayFinishedTasks'); // 
    exit;
    }


    public function displayFinishedTasks()  
    {
        $taskModel = new TaskModel();
        $finishedTasks = $taskModel->getFinishedTask();

        return View::make('Task/displayFinishedTasks', ['finishedTasks' => $finishedTasks]);
    }


    public function returnTasksAsUnfinished()
    {
        $unCompletedIds = $_SESSION['selectedIDsOfUncompletedTasks'];

        $fromTableName = 'finishedtasks';
        $toTableName = 'tasks';

        $taskModel = new TaskModel();
        $taskModel->addFinishedTaskToArchive($unCompletedIds, $fromTableName, $toTableName);

        header('Location: /MVC/srcForTodoList/Public/index.php/displayFinishedTasks');
        exit;
    }

    public function deleteTasksPermanently()
    {
        $unCompletedIds = $_SESSION['selectedIDsOfUncompletedTasks'];

        $taskModel = new TaskModel();
        $taskModel->deleteFinishedTasksPermanently($unCompletedIds);

        header('Location: /MVC/srcForTodoList/Public/index.php/displayFinishedTasks');
        exit;
    }

    public function updateTaskForm()
    {

        $taskId = $_GET['id'];

        $taskModel = new TaskModel();
        $task =  $taskModel->accessTaskFromDB($taskId);

        return View::make('Task/updateTask', ['task' => $task]);
    }

    public function updateTask()
    {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $dueDate = $_POST['dueDate'];
        $importantLevel = $_POST['importantLevel'];

        $taskModel = new TaskModel();
        $taskModel->updateTask($id ,$title, $dueDate, $importantLevel);

        header('Location: /MVC/srcForTodoList/Public/index.php/');
        exit;
    }
   
}