<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class Tasks extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
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

    /**
     * user relation to tasks
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * relation task and subtask and so on
     */
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
     * get one task and its subtasks(if they are)
     * @param int $id
     * @return mixed
     */
    public static function getTask(int $id)
    {
        return Tasks::where('id', '=', $id)->with('subtasks')->first();
    }

    /**
     * get all tasks hierarchically
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

    /**
     * get task, look for unfinished tasks
     * if there are unfinished task, don't update status
     * if there aren't update status
     * @param int $id
     * @param $status
     * @return JsonResponse
     */
    public static function updateSt(int $id, $status): JsonResponse
    {
        try {
            $task = self::getTask($id);
            $unfinished_tasks = array_filter($task->subtasks->toArray(), function ($item) {
                return $item['status'] == 'todo';
            });
            if (!empty($unfinished_tasks)) {
                return response()->json(['msg' => 'There are unfinished subtasks left'], 404);
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
     * if request have title -> search only by title
     * if request have priority range and status -> search by these parameters
     * sorted by the provided type for sort
     */
    public static function filter($attributes)
    {
        $query = DB::table('tasks');
        if ($attributes['title']) {
            $query->where('title', $attributes['title']);
        }
        if ($attributes['priority'] && $attributes['status']) {
            $query->where('status', $attributes['status'])
                ->whereBetween('priority', $attributes['priority']);
        }
        return $query->orderBy($attributes['sorted'])->get();
    }

    /**
     * find task, checking status, if it's not done -> delete.
     * @param int $id
     * @return JsonResponse
     */
    public static function deleteTask(int $id): JsonResponse
    {
        $task = Tasks::find($id);

        if (!$task) {
            return response()->json(['msg' => 'Error. Task not found.']);
        } elseif ($task->status === 'todo') {
            Tasks::destroy($id);
            return response()->json(['msg' => 'This task successfully deleted']);
        } else {
            return response()->json(['msg' => 'Error. This task is done']);
        }
    }
}
