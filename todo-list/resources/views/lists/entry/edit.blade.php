@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $todoList->description ?: ' a new todo list'}}</div>

                <div class="card-body">
                    {!! Form::model($entry, array('route' => array('entry.update'))) !!}
                        {!! Form::hidden('list_id', $todoList->id) !!}
                        {!! Form::hidden('id') !!}
                        <div class="row col-12 m-0">
                            {!! Form::label('description', 'Description:') !!}
                            {!! Form::text('description', null, ['class' => 'w-100 form-control', 'required']) !!}
                        </div>
                        <div class="row col-12 m-0">
                            {!! Form::label('priority', 'Priority:') !!}
                            {!! Form::number('priority', null,
                            	[
                            		'class' => 'w-100 form-control',
                            		'min' => $entry->lowestPossiblePriority(),
                            		'max' => $entry->highestPossiblePriority()
                            	]) !!}
                        </div>
                        <div class="row col-12 m-0 pt-3 form-group">
                        	<span class="p-2">
                            	{!! Form::label('status', 'Status:', ['class' => 'align-middle']) !!}
                            </span>
                            <span class="col-5 pl-1">
                            	{!! Form::select('status',
                            		$entry->getValidStatuses(), null,
                            		['class'=>'w-100 form-control', 'required']) !!}
                            </span>
                        	{!! Form::label('due_date', 'Due date	:', ['class' => 'p-2']) !!}
                          <!-- Another variation with a button -->
                          <div class="input-group col-4 mr-0 pr-0">
                            {!! Form::date('due_date', new DateTime($entry->due_date), ['class' => ' form-control ndatepicker', 'required']) !!}
                            <div class="input-group-append d-block">
                              <button class="btn btn-secondary" type="button">
                        		<i class="fa fa-calendar"></i>
                              </button>
                            </div>
                          </div>
                        </div>

                        <div class="row col-12 m-0">
                        	<div class="col-3">
                        		{!! Form::submit('Save', ['class' => 'btn btn-primary mt-2 w-100']) !!}
                        	</div>
                        	<div class="col-3">
                        		{!! Form::button('Delete', ['name' => 'delete', 'value' => 1, 'class' => 'btn btn-danger mt-2 w-100', 'type' => 'submit']) !!}
                        	</div>
                        </div>
                    {!! Form::close() !!}
            	</div>
            </div>
        </div>
    </div>
</div>
@endsection