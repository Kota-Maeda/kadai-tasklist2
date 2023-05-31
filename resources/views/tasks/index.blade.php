@extends('layouts.app')

@section('content')

    <div class="prose ml-4">
        <h2>タスク 一覧</h2>
    </div>

    @if (isset($tasks))
        <table class="table table-zebra w-full my-4">
            <thead>
                <tr>
                    <th>id</th>
                    <th>メッセージ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>
                    <td><a class="link link-hover text-info" href="#">{{ $task->id }}</a></td>
                    <td>{{ $task->content }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    {{-- タスク作成ページへのリンク --}}
    
    <a class="btn btn-primary" href="{{ route('tasks.create') }}">タスクの追加</a>
    
    @foreach ($tasks as $task)
    <tr>
        <td><a class="link link-hover texr-info" href="{{ route('tasks.show',$task->id) }}">{{ $task->id }}</a></td>
    </tr>
    @endforeach

@endsection