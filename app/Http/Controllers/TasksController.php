<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    protected $task;

    public function __construct(Tasks $task) {
        $this->task = $task;
    }

    public function store(Request $request) {
        $task = $this->task->createTask($request->all());
        return response()->json($task);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request) {
        try {
            $task = $this->task->updateTask($id, $request->all());
            return response()->json($task);
        }catch (ModelNotFoundException $exception) {
            return response()->json(['msg'  => $exception->getMessage()], 404);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get($id) {
        $task = $this->task->getsTask($id);
        if($task) {
            return response()->json($task);
        }
        return response()->json(['msg' => 'Tasks not found'], 404);
    }

    /**
     * @return JsonResponse
     */
    public function gets() {
        $tasks = $this->task->getsTask();
        return response()->json($tasks);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id) {
        try {
            $task = $this->task->deleteTask($id);
            return response()->json(['msg' => 'Post ' . $id . ' deleted successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['msg' => $exception->getMessage()], 404);
        }
    }
}
