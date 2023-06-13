<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;
use App\Models\Micropost;

class TasksController extends Controller
{
    public function index()
    {
        // メッセージ一覧を取得
        $tasks = Task::all();

        // メッセージ一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
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
        //$tasks = new Task;
        //$tasks->status = $request->status;
        //$tasks->content = $request->content;
        //$tasks->user_id = $request->user()->id;
        
        //$user_id2 = \DB::table('users')->get();
        
        //タスクを作成
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);

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
        //$tasks = Task::findOrFail($id);
        
        //タスク編集ビューでそれを表示
        //return view('tasks.edit', ['tasks' => $tasks ]);
        
        if(\Auth::check()){
            $tasks = Task::findOrFail($id);
            return view('tasks.edit', ['tasks' => $tasks ]);
        }
    }
    
    public function update(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',    
        ]);
        
        
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        //タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        
        /*
        $task = Task::findOrFail($id);
        
        $request->user()->tasks()->update([
            $task->status = $request->status,
            $task->content = $request->content,
        ]);
        $task->save();
        */
        
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
