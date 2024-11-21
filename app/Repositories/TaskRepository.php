<?php

namespace App\Repositories;
use App\Models\Task;
use App\Models\Project;
use App\Repositories\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasksWithProjects()
    {
        return Project::with('tasks')->get();
    }

    public function storeTask(array $data)
    {
        $priority = Task::where('project_id', $data['project_id'])->max('priority') + 1;

        return Task::create([
            'name' => $data['name'],
            'priority' => $priority,
            'project_id' => $data['project_id'],
        ]);
    }

    public function updateTask(int $id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask(int $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return true;
    }

    public function reorderTasks(array $tasks)
    {
        foreach ($tasks as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }
        return true;
    }
}