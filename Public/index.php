<?php

declare(strict_types= 1);

use App\Config;
use App\Router;
use App\App;
use App\Controller\HomeController;
use App\Controller\TaskController;

// - _ = + 5 6 7 8 9 0 () % & *

spl_autoload_register(function($class) {
    $path = __DIR__ . '/../'. str_replace('\\', '/', $class) . '.php';
    require $path;
});

require_once  '../env.php';

session_start();

define( 'VIEW_PATH',  __DIR__ . '/../View/' );
define('STORAGE_PATH',  __DIR__ . '/../Storage/' );

$router = new Router();

$router->get('/', [HomeController::class, 'index'])
       ->get('/addNewTask', [TaskController::class, 'addNewTask'])
       ->post('/storeTask', [TaskController::class, 'storeTask'] )
       ->post('/updateTaskStatus', [TaskController::class, 'toggleComplete'])
       ->post('/migrateFinishedTask', [TaskController::class, 'migrateFinishedTask'])
       ->get('/displayFinishedTasks' , [TaskController::class, 'displayFinishedTasks'])
       ->post('/updateTaskStatusFromArchive', [TaskController::class, 'toggleInComplete'])
       ->post('/returnTasksAsUnfinished', [TaskController::class, 'returnTasksAsUnfinished'])
       ->post('/deleteTasksPermanently',[TaskController::class, 'deleteTasksPermanently'])
       ->get('/updateTaskForm',[TaskController::class, 'updateTaskForm'])
       ->post('/updateTask', [TaskController::class, 'updateTask']);


(new App($router, ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']], (new Config($_ENV))))->run();