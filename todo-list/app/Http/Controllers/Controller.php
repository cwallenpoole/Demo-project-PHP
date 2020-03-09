<?php

namespace App\Http\Controllers;

use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use \Illuminate\Foundation\Bus\DispatchesJobs;
use \Illuminate\Foundation\Validation\ValidatesRequests;
use \Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\TodoList;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function fail(Request $request, Validator $validator) {
        if($request->wantsJson()) {
            return response()->json([
                'error' => $validator->messages()->first()
            ], 400);
        }
        Session::flash('error', $validator->messages()->first());
        return redirect()->back()->withInput();
    }

    protected function showListAsJson(TodoList $todoList, $entries = null) {
        $entries = $entries ?: $todoList->entries();
        response()->json([
            'data' => $todoList->toArray(),
            'children' => $entries->get()->toArray(),
            'owner' => $todoList->owner->toArray()
        ], 200, [], 128);
    }

    protected function redirectToList(Request $request, TodoList $todoList) {

        if($request->wantsJson()) {
            return $this->showListAsJson($todoList);
        }

        return redirect()->route('list.edit', ['todoList' => $todoList]);
    }
}
