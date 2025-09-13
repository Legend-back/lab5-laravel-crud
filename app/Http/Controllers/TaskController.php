<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TaskRepositoryInterface;

class TaskController extends Controller
{
    private TaskRepositoryInterface $tasks;

    public function __construct(TaskRepositoryInterface $tasks)
    {
        $this->tasks = $tasks;
    }

    public function showAll()
    {
        return response()->json($this->tasks->all(), 200);
    }

    public function store(Request $request)
    {
        $task = $this->tasks->create([
            'title' => $request->title ?? null,
            'completed' => $request->completed ?? false,
        ]);

        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = $this->tasks->find((int) $id);
        return response()->json($task, $task ? 200 : 404);
    }

    public function update(Request $request, $id)
    {
        $task = $this->tasks->update((int) $id, [
            'title' => $request->title ?? null,
            'completed' => $request->completed ?? false,
        ]);

        return response()->json($task, $task ? 200 : 404);
    }

    public function destroy($id)
    {
        $deleted = $this->tasks->delete((int) $id);
        return response()->json(['deleted' => $deleted], $deleted ? 200 : 404);
    }
}