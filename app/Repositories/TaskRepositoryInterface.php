<?php

namespace App\Repositories;

interface TaskRepositoryInterface
{
    public function getAllTasksWithProjects();
    public function storeTask(array $data);
    public function updateTask(int $id, array $data);
    public function deleteTask(int $id);
    public function reorderTasks(array $tasks);
}
