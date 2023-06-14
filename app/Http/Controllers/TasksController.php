<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;

class TasksController extends Controller
{
    public function index()
    {
        $data = [];
        
        if(\Auth::check()){
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // メッセージ一覧ビューでそれを表示
        return view('tasks.index', $data);
        
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
        
        //認証済みユーザがその投稿者の所有者であれば
        if(\Auth::id() == $tasks->user_id){
        //タスク詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $tasks,
            ]);
        }
        else{
            return redirect('/');
        }

    }
    
    public function edit($id)
    {
        //idの値でタスクを検索して取得
        //$tasks = Task::findOrFail($id);
        
        //タスク編集ビューでそれを表示
        //return view('tasks.edit', ['tasks' => $tasks ]);
        
        //認証済みユーザがその投稿者の所有者であれば
        if(\Auth::id() == $tasks->user_id){
            if(\Auth::check()){
                $tasks = Task::findOrFail($id);
                return view('tasks.edit', ['tasks' => $tasks ]);
            }
        }
        else{
            return redirect('/');
        }
    }
    
    public function update(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',    
        ]);
        
        //認証済みユーザがその投稿者の所有者であれば
        if(\Auth::id() == $tasks->user_id){
            //idの値でタスクを検索して取得
            $task = Task::findOrFail($id);
            //タスクを更新
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
        }
        else{
            return redirect('/');
        }
        
        
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
        //認証済みユーザがその投稿者の所有者であれば
        if(\Auth::id() == $tasks->user_id){
            //idでタスクを検索して削除
            $task = Task::findOrFail($id);
            //タスクを削除
            $task->delete();
        }
        
        //トップページへリダイレクト
        return redirect('/');
    }
}
