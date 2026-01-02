<?php

declare(strict_types=1);

namespace App\Controller;

use App\View;
use App\Models\TaskModel;

class HomeController
{
    public function  index()  
    {
        $keyword = $_GET['search'] ?? '';
        $_SESSION['currentSearch'] = $keyword;

        $taskModel = new TaskModel();
        $data = $taskModel->searchTasks($keyword); 

        return View::make('index', ['data' => $data, 'keyword' => $keyword]);
    }  
}