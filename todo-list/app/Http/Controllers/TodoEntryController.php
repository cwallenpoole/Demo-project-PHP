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
    public function create(Request $request, TodoList $todoList)
    {
        return view('lists.entry.edit', ['todoList' => $todoList, 'entry' => new TodoEntry()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TodoEntry  $todoEntry
     * @return \Illuminate\Http\Response
     */
    public function show(TodoList $todoList, $index)
    {
        $entries = $todoList->entries;
        if($index >= count($entries)) {
            abort(404);
            return;
        }
        $todoEntry = $entries[$index];
        return response()->json([
                'data' => $todoEntry->toArray(),
                'parent' => collect($todoList->toArray())->except(['entries']),
                'owner' => $todoList->owner->toArray()
            ], 200, [], 128
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param TodoList $todoList
     * @param TodoEntry $todoEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, TodoList $todoList, TodoEntry $todoEntry)
    {
        return view('lists.entry.edit', ['todoList' => $todoList, 'entry' => $todoEntry]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->post('id');
        $entry = $id? TodoEntry::findOrFail($id): null;

        if($request->post('delete')) {
            return $this->destroy($request, $entry);
        }

        $validator = Validator::make($request->all(), [
            'list_id' => 'required|numeric',
            'id' => 'nullable|numeric|min:1',
            'description' => 'required|max:255',
            'priority' => 'required|digits_between:1,9',
            'status' => 'required|max:20',
            'due_date' => 'required|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return $this->fail($request, $validator);
        }

        // We can'd to the raw updateOrCreate because we are dealing with the possibility of
        // multiple time formats.
        $data = ['due_date' => date('Y-m-d', strtotime($request->post('due_date')))] + $validator->validated();

        if(!$id) {
            $entry = new TodoEntry();
        }
        $entry->update($data);
        $entry->save();

        return $this->redirectToList($request, $entry->parent);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TodoEntry  $todoEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TodoEntry $todoEntry)
    {
        $todoList = $todoEntry->parent;
        $todoEntry->delete();
        if($request->wantsJson()) {
            return response('', 201);
        }
        return $this->redirectToList($request, $todoList);
    }
}
