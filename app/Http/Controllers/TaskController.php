<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Repositories\TaskRepositoryInterface;

class TaskController extends Controller
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index()
    {
        $projects = $this->taskRepository->getAllTasksWithProjects();
        return view('tasks.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required|exists:projects,id',
        ]);

        $this->taskRepository->storeTask($request->only(['name', 'project_id']));

        return redirect()->route('task.list');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        $this->taskRepository->updateTask($id, $request->only(['name']));

        return redirect()->route('task.list');
    }

    public function destroy($id)
    {
        $this->taskRepository->deleteTask($id);

        return redirect()->route('task.list');
    }

    public function reorder(Request $request)
    {
        $this->taskRepository->reorderTasks($request->tasks);

        return response()->json(['status' => 'success']);
    }
}
