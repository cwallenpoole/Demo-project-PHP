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
                            {!! Form::text('due_date', null, ['class' => ' form-control datepicker', 'required']) !!}
                            <div class="input-group-append d-block">
                              <button class="btn btn-secondary" type="button">
                        		<i class="fa fa-calendar"></i>
                              </button>
                            </div>
                          </div>
                        </div>

                        <div class="row col-12 m-0">
                        	{!! Form::submit('Save', ['class' => 'btn btn-primary mt-2']) !!}
                        </div>
                    {!! Form::close() !!}
            	</div>
            </div>
        </div>
    </div>
</div>
@endsection