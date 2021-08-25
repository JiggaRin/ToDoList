<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    /**
     * @param array $attributes
     * @return Tasks
     */

    public function createTask(array $attributes) {
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
    public function getTask(int $id) {
        $task = $this->where('id',$id)->first();
        return $task;
    }

    /**
     * @return Tasks[]|Collection
     */
    public function getsTask() {
        $task = $this::all();
        return $task;
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return mixed
     */
    public function updateTask(int $id, array $attributes) {
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
     * @param int $id
     * @return mixed
     */
    public function deleteTask(int $id) {
        $task = $this->getTask($id);
        if($task == null) {
            throw new ModelNotFoundException('Task not found');
        }
        return $task->delete();
    }
}
