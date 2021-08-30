<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

class Tasks extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'parent_id',
        'status',
        'priority',
        'title',
        'description',
        'completion_time',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany('App\Models\Tasks', 'parent_id')->with('subtasks');
    }


    /**
     * @param Tasks $new_task
     * @return bool
     */
    public static function createTask(Tasks $new_task): bool
    {
        return $new_task->save();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public static function getTask(int $id)
    {
        return Tasks::where('id', $id)->with('subtasks')->first();
    }

    /**
     * @return Collection|Tasks[]
     */
    public static function getAllTask()
    {
        return Tasks::where('parent_id', '=', null)->with('subtasks')->get();
    }

    /**
     * @param int $id
     * @param array $update_task
     * @return mixed
     */
    public static function updateTask(int $id, array $update_task)
    {
        $task = self::find($id);
        if ($task == null) {
            throw new ModelNotFoundException("Can't find task");
        }
        $task->parent_id = $update_task['parent_id'];
        $task->status = $update_task['status'];
        $task->priority = $update_task['priority'];
        $task->title = $update_task['title'];
        $task->description = $update_task['description'];
        $task->user_id = $update_task['user_id'];
        $task->completion_time = $update_task['completion_time'];
        return $task->save();
    }

    public static function updateSt(int $id, int $status): JsonResponse
    {
        try {
            $task = self::getTask($id);
            $unfinished_tasks = array_filter($task->subtasks->toArray(), function ($item) {
                return $item['status'] === 0;
            });
            if (!empty($unfinished_tasks)) {
                return response()->json(['msg' => 'There is unfinished subtasks left'], 404);
            } else {
                $task->status = $status;
                $task->save();
                return response()->json(['msg' => 'Status update success']);
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['msg' => 'Something went wrong']);
        }
    }

    /**
     * @return mixed
     */
    public function filter($attributes)
    {
        $query = Tasks::select();
        if ($attributes['title']) {
            $query->where('title', $attributes['title']);
            $matchAttributes = ['title' => $attributes['title']];
        }
        if($attributes['priority']) {
            if(is_array($attributes['priority'])){
                $query->whereBetween('priority', $attributes['priority'][0], $attributes['priority'][1]);
            } else {
                $query->where('priority', $attributes['priority']);
            }
        } else {
            $matchAttributes = [
                'status' => $attributes['status'],
                'priority' => $attributes['priority'],
            ];
        }

        return Tasks::where($matchAttributes)->orderBy($attributes['sorted'], 'asc')->get();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public static function deleteTask(int $id): JsonResponse
    {
        $task = Tasks::find($id);

        if (!$task) {
            return response()->json(['msg' => 'Error. Task not found.']);
        } elseif ($task->status == 0) {
            Tasks::destroy($id);
            return response()->json(['msg' => 'This task successfully deleted']);
        } else {
            return response()->json(['msg' => 'Error. This task is not done']);
        }
    }
}
