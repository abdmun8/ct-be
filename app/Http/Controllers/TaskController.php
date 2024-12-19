<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Interfaces\TaskRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    private TaskRepositoryInterface $taskRepositoryInterface;

    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = [];
        if ($request->completed) {
            $query['completed'] = $request->completed == "true" ?? false;
        }
        $data = $this->taskRepositoryInterface->index($query);
        return ApiResponseClass::sendResponse(TaskResource::collection($data), '', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $details = [
            'title' => $request->title,
            'description' => $request->description,
            'completed' => $request->completed,
        ];

        if (! $details['completed']) {
            $details['completed'] = false;
        }

        DB::beginTransaction();
        try {
            $task = $this->taskRepositoryInterface->store($details);

            DB::commit();
            return ApiResponseClass::sendResponse(new TaskResource($task), 'Task Create Successful', 201);
        } catch (\Exception $ex) {
            var_dump($ex);
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = $this->taskRepositoryInterface->getById($id);

        return ApiResponseClass::sendResponse(new TaskResource($task), '', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $updateDetails = [
            'description' => $request->description,
            'completed' => $request->completed,
        ];
        if ($request->title) {
            $updateDetails['title'] = $request->title;
        }
        DB::beginTransaction();
        try {
            $task = $this->taskRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ApiResponseClass::sendResponse('Task Update Successful', '', 201);
        } catch (\Exception $ex) {
            var_dump($ex);
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->taskRepositoryInterface->delete($id);
    }
}
