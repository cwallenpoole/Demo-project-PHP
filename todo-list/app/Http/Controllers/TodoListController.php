<?php

namespace App\Http\Controllers;

use App\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class TodoListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lists/edit', ['todoList' => new TodoList(), 'user' => Auth::user()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, TodoList $todoList)
    {
        $sortStr = $request->get('sort');
        $finalSort = [];

        if($sortStr) {
            $sortPieces = explode(',', $sortStr);
            foreach ($sortPieces as $piece) {
                $clean = trim($piece);
                if(!$clean) {
                    continue;
                }
                $parts = explode(' ', $clean);
                $finalSort[$parts[0]] = $parts[1] ?? 'asc';
            }
        }
        $finalSort += [
            'priority' => 'desc',
            'due_date' => 'asc',
            'id' => 'desc',
            'description' => 'asc',
            'created_at' => 'asc',
            'updated_at' => 'asc',
            // Status is complicated by the fact that it's a string. The "right" way to handle this
            // is by creating a DB::raw for the order by or switching the database to use integers.
            'status' => 'desc'
        ];

        $entries = $todoList->entries();
        foreach ($finalSort as $column => $direction) {
            $entries->orderBy($column, $direction);
        }

        return response()->json([
            'data' => $todoList->toArray(),
            'children' => $entries->get()->toArray(),
            'owner' => $todoList->owner->toArray()
        ], 200, [], 128);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function edit(TodoList $todoList)
    {
        return view('lists.edit', ['todoList' => $todoList, 'user' => Auth::user()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'id' => 'nullable|numeric|min:1',
            'description' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withInput();
        }

        $list = TodoList::updateOrCreate($validator->validated());
        return redirect()->route('list.edit', ['todoList' => $list->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function destroy(TodoList $todoList)
    {
        $todoList->delete();
        return redirect()->route('home');
    }
}
