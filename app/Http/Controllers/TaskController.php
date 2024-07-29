<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "message" => "User not authenticated"
            ], 401);
        }

        $tasks = Task::where("user_id", $user->id)->where("is_completed", 0)->get();

        return response()->json([
            "message" => "Success get tasks",
            "data" => $tasks
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|string",
            "description" => "required|string",
            "due_date" => "date",
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "Invalid Input",
                "errors" => $validator->errors()
            ], 400);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "message" => "User not authenticated"
            ], 401);
        }

        $task = Task::create([
            "user_id" => $user->id,
            "title" => $request->input("title"),
            "description" => $request->input("description"),
            "due_date" => $request->input("due_date"),
        ]);

        return response()->json([
            "message" => "Success created task",
            "data" => $task
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "message" => "User not authenticated"
            ], 401);
        }

        $task = Task::where("user_id", $user->id)->where("id", $id)->first();

        if(!$task) {
            return response()->json([
                "message" => "Task not found",
            ], 400);
        }

        if ($request->has('title')) {
            $task->title = $request->input('title');
        }

        if ($request->has('description')) {
            $task->description = $request->input('description');
        }

        if ($request->has('due_date')) {
            $task->due_date = $request->input('due_date');
        }

        return response()->json([
            "message" => "Success edit taskk",
            "data" => $task
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id,Task $task)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "message" => "User not authenticated"
            ], 401);
        }

        $task = Task::where("user_id", $user->id)->where("id", $id)->first();

        if(!$task) {
            return response()->json([
                "message" => "Task not found",
            ], 400);
        }

        $task->delete();

        return response()->json([
            "message" => "Success delete task",
        ], 200);
    }

    public function is_completed($id) {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "message" => "User not authenticated"
            ], 401);
        }

        $task = Task::where("user_id", $user->id)->where("id", $id)->first();

        if(!$task) {
            return response()->json([
                "message" => "Task not found",
            ], 400);
        }

        $task->is_completed = 1;
        $task->save();

        return response()->json([
            "message" => "Success done task",
        ], 200);
    }
}
