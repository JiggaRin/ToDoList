<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TasksController extends Controller
{

    public function create(Request $request): JsonResponse
    {
        $task = new Tasks($request->all());
        Tasks::createTask($task);
        return response()->json($task);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        try {
            $updated_task = Tasks::updateTask($id, $request->all());
            return response()->json($updated_task);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['msg' => $exception->getMessage()], 404);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus($id, Request $request): JsonResponse
    {
        try {
            $updatedStatus = Tasks::updateSt($id, $request['status']);
            return response()->json($updatedStatus);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['msg' => $exception->getMessage()], 404);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $tasks = Tasks::getAllTask();
        return response()->json($tasks);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function filter(Request $request): JsonResponse
    {
        $task = Tasks::filter($request);
        if(!empty($task)) {
            return response()->json($task);
        } else {
            return response()->json(['msg' => 'Sorry, no match']);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        return Tasks::deleteTask($id);
    }
}
