<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

use App\Http\Controllers\UsersController;

use resources\auth;

//Route::get('/', [TasksController::class, 'index']);

//下のコメントアウトのコードをまとめた機能を持つ便利なコード
Route::resource('tasks', TasksController::class);


//CRUD


//タスクの個別詳細ページを表示
//Route::get('tasks/{id}', [TasksController::class, 'show']);
//タスクの新規登録を処理
//Route::post('tasks', [TasksController::class, 'store']);
//タスクの更新処理
//Route::put('tasks/{id}', [TasksController::class, 'update']);
//タスクを削除
//Route::delete('tasks/{id}', [TasksController::class, 'destroy']);

//index: showの補助ページ
//Route::get('/', [TasksController::class, 'index'])->name('tasks.index');
//create: 新規作成用のフォームページ
//Route::get('tasks/create', [TasksController::class, 'create'])->name('tasks.create');
//edit: 更新用フォームページ
//Route::get('tasks/{id}/edit', [TasksConreoller::class, 'edit'])->name('tasks.edit');

Route::get('/', function () {return view('dashboard');});

Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', UsersController::class, ['only' => ['index', 'show']]);
});