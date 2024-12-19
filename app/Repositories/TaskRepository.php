<?php

namespace App\Repositories;

use App\Models\Task;
use App\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function index($query = array())
    {
        if (isset($query['completed'])) {
            return Task::where('completed', $query['completed'])->get();
        }
        return Task::all();
    }

    public function getById($id)
    {
        return Task::findOrFail($id);
    }

    public function store(array $data)
    {
        return Task::create($data);
    }

    public function update(array $data, $id)
    {
        return Task::whereId($id)->update($data);
    }

    public function delete($id)
    {
        Task::destroy($id);
    }
}
