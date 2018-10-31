<?php

namespace App\Http\Controllers;

use App\Task;
use App\Link;

class GanttController extends Controller
{
    public function get(){
        $tasks = new Task();
        $links = new Link();
		
        return response()->json([
            "data" => $tasks->all(),
            "links" => $links->all(),
        ]);
    }
	
	public function store(Request $request){
		$task = new Task();
	 
		$task->text = $request->text;
		$task->start_date = $request->start_date;
		$task->duration = $request->duration;
		$task->progress = $request->has("progress") ? $request->progress : 0;
		$task->parent = $request->parent;
		$task->sortorder = Task::max("sortorder") + 1;
	 
		$task->save();
	 
		return response()->json([
			"action"=> "inserted",
			"tid" => $task->id
		]);
	}
}
