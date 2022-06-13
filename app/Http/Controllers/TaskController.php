<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task; //追加

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) { 
            //タスク一覧取得
            $tasks = Task::all();
        
            //タスク一覧ビューをそれで表示
            return view('tasks.index', [
                'tasks' => $tasks,
            ]);
            
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
        }
        else {
            return view('welcome');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        //タスク作成ビュー表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:100',
        ]);
        //タスク作成
        $task = new Task;
        $task->status = $request->status; //追加
        $task->content = $request->content;
        $task->user_id = \Auth::id();
        $task->save();
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //id値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
        //タスク表示
        if (\Auth::id() === $task->user_id) {
            $task->view();
        }
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' =>$task,
        ]);
        
        //タスク編集
        if (\Auth::id() === $task->user_id) {
            $task->edit();
        }
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',    // 追加
            'content' => 'required|max:100'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        //タスク削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        //トップページへリダイレクトさせる
        return redirect('/');
    }
}
