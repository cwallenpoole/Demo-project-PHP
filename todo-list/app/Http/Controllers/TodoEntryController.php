<?php

namespace App\Http\Controllers;

use App\TodoEntry;
use Illuminate\Http\Request;
use App\TodoList;

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
     * @param  \App\TodoEntry  $todoEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TodoEntry $todoEntry)
    {
        //
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
