<?php

namespace App\Http\Controllers;

use App\TodoEntry;
use Illuminate\Http\Request;
use App\TodoList;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class TodoEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, TodoList $todoList)
    {
        return view('lists.entry.edit', ['todoList' => $todoList, 'entry' => new TodoEntry()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TodoEntry  $todoEntry
     * @return \Illuminate\Http\Response
     */
    public function show(TodoEntry $todoEntry)
    {
        return view('lists.entry.view');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TodoEntry  $todoEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(TodoEntry $todoEntry)
    {
        return view('lists.entry.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'list_id' => 'required|numeric',
            'id' => 'nullable|numeric|min:1',
            'description' => 'required|max:255',
            'priority' => 'digits:1|required',
            'status' => 'required|max:20',
            'due_date' => 'required|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withInput();
        }

        $entry = TodoEntry::updateOrCreate(
            ['due_date' => date('Y-m-d', strtotime($request->post('due_date')))] + $validator->validated()
        );
        return redirect()->route('list.edit', ['todoList' => $entry->parent->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TodoEntry  $todoEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(TodoEntry $todoEntry)
    {
        //
    }
}
