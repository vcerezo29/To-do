<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::all()->groupBy('isImportant');

        return response()->json($todos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        $validated = $request->validated();

        $newTodo = Todo::create([
            'description' => $validated['description'],
            'status' => 0,
            'isImportant' => 0

        ]);

        return response()->json([
            "task" => $newTodo->fresh(),
            "message" => "Task Successfully Mark as Complete"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $target = Todo::find($id);

        if (is_null($target))
            return response()->json([
                'message' => 'Not found'
            ], 404);

        return response()->json($target, 200);
    }

    public function markComplete(string $id)
    {
        $target = Todo::find($id);
        if (is_null($target))
            return response()->json([
                'message' => 'Not found'
            ], 404);

        $target->status = 1;

        $target->save();

        return response()->json([
            "task" => $target,
            "message" => "Task Successfully Mark as Complete"
        ], 200);
    }
    public function markImportant(string $id)
    {
        $target = Todo::find($id);
        if (is_null($target))
            return response()->json([
                'message' => 'Not found'
            ], 404);

        $target->isImportant = 1;

        $target->save();

        return response()->json([
            "task" => $target,
            "message" => "Task Successfully Mark as Important"
        ], 200);
    }
    public function markUnimportant(string $id)
    {
        $target = Todo::find($id);
        if (is_null($target))
            return response()->json([
                'message' => 'Not found'
            ], 404);    

        $target->isImportant = 0;

        $target->save();

        return response()->json([
            "task" => $target,
            "message" => "Task Successfully Mark as Unimportant"
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, string $id)
    {
        $target = Todo::find($id);
        if (is_null($target))
            return response()->json([
                'message' => 'Not found'
            ], 404);
        $validated = $request->validated();

        $target->update([
            "description" => $validated["description"]
        ]);

        return response()->json([
            "task" => $target,
            "message" => "Task Successfuly Updated"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $target = Todo::find($id);
        if (is_null($target))
            return response()->json([
                'message' => 'Not found'
            ], 404);
        $target->delete();

        return response()->json([
            'message' => 'Successfully Deleted.'
        ], 200);
    }
}
