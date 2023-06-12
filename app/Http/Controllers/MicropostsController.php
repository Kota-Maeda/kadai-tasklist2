<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Micropost;
use App\Models\Task;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            //$microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                //'microposts' => $microposts,
            ];
            
            // dashboardビューでそれらを表示
            return view('dashboard', $data);
            
            //タスク一覧を取得
            $tasks = Task::all();
            
            //タスク一覧ビューでそれを表示
            return view('tasks.index', ['tasks' => $tasks,]);
        }
        
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
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ]);
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        //$request->user()->microposts()->create([
            //'content' => $request->content,
        //]);
        
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
        //return redirect('/');
        
        // 前のURLへリダイレクトさせる
        return back();
    }
    
    public function destroy($id)
    {
        // idの値で投稿を検索して取得
        $micropost = \App\Models\Micropost::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
            return back()
                ->with('success','Delete Successful');
        }
                //idでタスクを検索して削除
        $task = Task::findOrFail($id);
        //タスクを削除
        $task->delete();

        // 前のURLへリダイレクトさせる
        return back()
            ->with('Delete Failed');
    }
    
        public function show($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザーの投稿一覧を作成日時の降順で取得
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        //idの値でタスクを検索して取得
        $tasks = Task::findOrFail($id);
        
        //タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $tasks,
        ]);
        
        // ユーザ詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
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
            'status' => 'required|max:10',    
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
}
