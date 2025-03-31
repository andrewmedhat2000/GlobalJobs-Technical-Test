<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskDependencyRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::where('assignee_id',auth()->id())->with(['assignee', 'dependencies'])
                     ->withFilters($request->all())
                     ->get();

        return response()->json($tasks);
    }


    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'assignee_id' => $request->assignee_id,
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ], 201);
    }


    public function show(Task $task)
    {
        if (! Auth::user()->isManager() && $task->assignee_id != auth()->id()) {
            return response()->json([
                'message' => 'You Must Be A Manager Or Own This Task'
            ], 422);
        }
        return response()->json($task->load(['assignee', 'dependencies']));
    }


    public function update(UpdateTaskRequest $request, Task $task)
    {
        $updatableFields = $request->only(['title', 'description', 'due_date','assignee_id']);

        if (Auth::user()->isManager()) {
            $updatableFields['assignee_id'] = $request->assignee_id;
        }

        if ($request->has('status')) {

            if (! Auth::user()->isManager() && $task->assignee_id != auth()->id()) {
                return response()->json([
                    'message' => 'You Must Be A Manager Or Own This Task'
                ], 422);
            }

            if ($request->status === 'completed' && !$task->canComplete()) {
                return response()->json([
                    'message' => 'Cannot complete task with incomplete dependencies'
                ], 422);
            }
            $updatableFields['status'] = $request->status;
        }

        $task->update($updatableFields);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task
        ]);
    }

    public function addDependency(TaskDependencyRequest $request, Task $task)
    {
        if ($task->id === $request->dependency_id) {
            return response()->json([
                'message' => 'A task cannot depend on itself'
            ], 422);
        }

        $task->dependencies()->attach($request->dependency_id);

        return response()->json([
            'message' => 'Dependency added successfully'
        ]);
    }

    public function removeDependency(Task $task, Task $dependency)
    {
        $task->dependencies()->detach($dependency->id);

        return response()->json([
            'message' => 'Dependency removed successfully'
        ]);
    }
}
