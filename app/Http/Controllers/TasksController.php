<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //タスク一覧を取得
        $tasks = Task::all();
        
        //タスク一覧ビューでそれを表示
        return view('tasks.index', ['tasks' => $tasks,]);
    }
    
    public function create()
    {
        $tasks = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'tasks' => $tasks,
        ]);
    }
    
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',
        ]);
        
        //タスクを作成
        $tasks = new Task;
        $tasks->status = $request->status;
        $tasks->content = $request->content;
        $tasks->save();
        
        //トップページにリダイレクトさせる
        return redirect('/');
    }
    
    public function show($id)
    {
        //idの値でタスクを検索して取得
        $tasks = Task::findOrFail($id);
        
        //タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $tasks,
        ]);
    }
    
    public function edit($id)
    {
        //idの値でタスクを検索して取得
        $tasks = Task::findOrFail($id);
        
        //タスク編集ビューでそれを表示
        return view('tasks.edit', ['tasks' => $tasks ]);
    }
    
    public function update(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:20',    
        ]);
        
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        //タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }
    
    public function destroy($id)
    {
        //idでタスクを検索して削除
        $task = Task::findOrFail($id);
        //タスクを削除
        $task->delete();
        
        //トップページへリダイレクト
        return redirect('/');
    }
}
