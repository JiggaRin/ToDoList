<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'completion_time'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subtasks() {
        return $this->hasMany('App\Models\Tasks', 'parent_id');
    }

    public function subsubtasks()
    {
        return $this->belongsToMany('App\Models\Tasks', 'parent_id');
    }

    /**
     * @param array $attributes
     * @return Tasks
     */
    public function createTask(array $attributes): Tasks
    {
        $task = new self();
        $task->parent_id = $attributes['parent_id'];
        $task->status = $attributes['status'];
        $task->priority = $attributes['priority'];
        $task->title = $attributes['title'];
        $task->description = $attributes['description'];
        $task->completion_time = $attributes['completion_time'];
        $task->save();

        return $task;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getTask(int $id)
    {
        $task = $this->where('id', $id)->first();
        return $task;
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
     * @param array $attributes
     * @return mixed
     */
    public function updateTask(int $id, array $attributes)
    {
        $task = $this->getTask($id);
        if ($task == null) {
            throw new ModelNotFoundException("Can't find task");
        }
        $task->parent_id = $attributes['parent_id'];
        $task->status = $attributes['status'];
        $task->priority = $attributes['priority'];
        $task->title = $attributes['title'];
        $task->description = $attributes['description'];
        $task->completion_time = $attributes['completion_time'];
        $task->save();
        return $task;
    }

    /**
     * @return mixed
     */
    public function filter($attributes)
    {
        if ($attributes['title']) {
            $matchAttributes = ['title' => $attributes['title']];
        } else {
            $matchAttributes = [
                'status' => $attributes['status'],
                'priority' => $attributes['priority'],
            ];
        }

        return Tasks::where($matchAttributes)->orderBy($attributes['sorted'], 'asc')->get();
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function deleteTask($attributes)
    {
        $task = $this->getTask($attributes['id']);
        if ($task == null) {
            throw new ModelNotFoundException('Task not found');
        }
        if($task['status'] == 0) {
            return $task->delete();
        } else {
            throw new ModelNotFoundException('This task is already finished');
        }
    }
}
