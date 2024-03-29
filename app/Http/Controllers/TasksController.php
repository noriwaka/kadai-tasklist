<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use Illuminate\Support\Facades\Auth; // この行を追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Auth::user()ログイン中のユーザー取得
        // ->id->get() 上記のidを取得
        //もしログインしていたら、ログインしているユーザーのidと同じのuser_idカラム（タスクモデル）を取得
        if(Auth::check()) {
            $tasks = Task::where('user_id', Auth::user()->id)->get();
            return view('dashboard', [
            'tasks' => $tasks,
            ]);
        } else {
            return view('dashboard');
        }
        // ログインユーザーのタスクのみを取得
        // $tasks = Task::where('user_id', Auth::id())->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        //タスク作成用のビューを表示
        return view('tasks.create', [
            'tasks' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //  新規登録処理
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        $task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id = Auth::id(); // 現在のユーザーIDを設定
        $task->save();
        
        //保存後にトップページへリダイレクト
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
         
         $task = Task::findOrFail($id);

        // ログインしているユーザーのIDとタスクのuser_idを比較
        if (Auth::id() != $task->user_id) {
            // IDが一致しない場合はトップページにリダイレクト
            return redirect('/');
        } else {
            // IDが一致する場合はタスク詳細ビューを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // id(引数)の値で検索して取得
        $task = Task::findOrFail($id);
        
        if (Auth::id() != $task->user_id) {
            return redirect('/');
        } else {
        
        // ビューで表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
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
        
        //バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        $task = Task::findOrFail($id);
        
        if (Auth::id() != $task->user_id) {
            return redirect('/');
        } else {
            
            // タスク更新するために、POSTデータを代入して更新
            $task->content = $request->content;
            $task->status = $request->status;
            $task->save();
            
            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        if (Auth::id() != $task->user_id) {
            return redirect('/');
        } else {
        
            $task->delete();
            
            return redirect('/');
        }
    }
}
