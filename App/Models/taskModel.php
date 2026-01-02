<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use EmptyIterator;
use Exception;

class TaskModel extends Model
{
    public function addTaskToDB(array $taskInfo)
    {
        $stmt =  $this->db->prepare('INSERT INTO tasks(title, taskStatus, dueDate, createdAt, importantLevel)
                                    VALUES (?, ?, ?, now(), ?)');

        $status = 'Pending';
        
        $stmt->execute([$taskInfo['title'], $status, $taskInfo['dueDate'], $taskInfo['level']]);
    }


    public function accessTasksFromDb()
    {
        $stmt = $this->db->prepare('SELECT * FROM tasks ORDER BY id DESC');
        $stmt->execute();

        $data = $stmt->fetchAll();
        return $data;
    }


    public function updateTaskStatus(array $completedIds): void
    {
        // First: Set ALL tasks to 'Pending' (or whatever your default is)
        $this->db->query("UPDATE tasks SET taskStatus = 'Pending'");

        // If any are checked, set those to 'Done'
        if (!empty($completedIds)) {
            $placeholders = str_repeat('?, ', count($completedIds) - 1) . '?';
            $sql = "UPDATE tasks SET taskStatus = 'Done' WHERE id IN ($placeholders)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($completedIds);  // Pass the array directly
        }
    }

    public function updateTaskStatusFromArchive(array $completedIds): void
    {
        // First: Set ALL tasks to 'Pending' (or whatever your default is)
        $this->db->query("UPDATE finishedtasks SET taskStatus = 'Done'");

        // If any are checked, set those to 'Done'
        if (!empty($completedIds)) {
            $placeholders = str_repeat('?, ', count($completedIds) - 1) . '?';
            $sql = "UPDATE finishedtasks SET taskStatus = 'Pending' WHERE id IN ($placeholders)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($completedIds);  // Pass the array directly
        }
    }

    private function accessFinishedTasks(array $completedIds, string $tableName): array
    {
        $placeholders = str_repeat('?, ', count($completedIds) - 1) . '?';
        $sql = "SELECT * FROM `$tableName` WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($completedIds);

        $finishedTasks = $stmt->fetchAll();

        return $finishedTasks ?? [];
    }

    private function deleteTask(array $completedIds, string $fromTableName): array
    {

        $finishedTasks = [];

        if(!empty($completedIds))
        {
            $finishedTasks = $this->accessFinishedTasks($completedIds, $fromTableName);
            $placeholder = str_repeat('?, ', count($completedIds) - 1) . '?';
            $query = "DELETE FROM `$fromTableName` WHERE id IN ($placeholder)";
            $stmt = $this->db->prepare($query);
  
            $stmt->execute($completedIds);     
        }

        return $finishedTasks;
    }

    public function addFinishedTaskToArchive(array $completedIds, string $fromTableName, string $toTableName = '' )
    {

        $this->db->beginTransaction();
        try 
        {

            $finishedTasks = $this->deleteTask($completedIds, $fromTableName);

            $query = "INSERT INTO `$toTableName`(title, taskStatus, dueDate, createdAt, importantLevel)
                                VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($query);
            foreach($finishedTasks as $row)
            {
                $stmt->execute([$row['title'], $row['taskStatus'], $row['dueDate'], $row['createdAt'], $row['importantLevel']]);
            }
        }
        catch(Exception $e)
        {
            if($this->db->inTransaction()) 
            {
                $this->db->rollback();
            }
           
            throw $e;
        }
       
    }

    public function getFinishedTask()
    {
        $stmt = $this->db->prepare('SELECT * FROM finishedtasks ORDER BY id DESC');
        $stmt->execute();

        $finishedTask = $stmt->fetchAll();

        return $finishedTask ?? [];
    }

    public function deleteFinishedTasksPermanently(array $unCompletedIds)
    {
        $placeholder = str_repeat('?, ', count($unCompletedIds) - 1) . '?';
        $query = "DELETE FROM finishedtasks WHERE id IN ($placeholder)"; 
        $stmt = $this->db->prepare($query);

        $stmt->execute($unCompletedIds);
    }

    public function searchTasks(string $keyword = '')
    {
        if($keyword === '')
        {
            return $this->accessTasksFromDb();
        }

        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE title LIKE ? ORDER BY id DESC');
        $stmt->execute(["%$keyword%"]);
        
        $data = $stmt->fetchAll();

        return $data;
    }

    public function accessTaskFromDB(string $selectedId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$selectedId]);

        $task = $stmt->fetch();

        return $task;
    }

    public function updateTask(string $selectedId, string $title, string $dueDate, string $importantLevel)
    {
        $query = "UPDATE tasks SET title = ?, dueDate = ?, importantLevel = ? WHERE id = ?";
        
        $stmt = $this->db->prepare($query);

        $stmt->execute([$title, $dueDate, $importantLevel, $selectedId]);
    }


}