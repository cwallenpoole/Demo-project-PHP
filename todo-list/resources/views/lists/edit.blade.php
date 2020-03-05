@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $todoList->description ?: ' a new todo list'}}</div>

                <div class="card-body">

                    {!! Form::model($todoList, array('route' => array('list.update'))) !!}
                        {!! Form::hidden('user_id') !!}
                        {!! Form::hidden('id') !!}
                        {!! Form::label('description', 'This is your list\'s name:') !!}
                        {!! Form::text('description', null, ['class' => 'w-100']) !!}
                        {!! Form::submit('Save', ['class' => 'btn btn-primary mt-2']) !!}
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="card mt-5">
            	<div class="card-header">Contents</div>
            	<div class="card-body">
            		<table class="w-100 use-datatable">
            			<thead>
            				<tr>
            					<th>Description</th>
            					<th>Priority</th>
            					<th>Due Date</th>
            					<th>Status</th>
            				</tr>
            			</thead>
            			<tbody>
    					@foreach ($todoList->entries as $entry)
    					<tr>
    						<td>{!!
    						link_to_route(
    							'entry.edit', $list->description,
    							['todoList' => $todoList->id, 'id' => $entry->id]) !!}</td>
    						<td>{{ $entry->priority }}</td>
    						<td>{{ $entry->due_date }}</td>
    						<td>{{ $entry->status }}</td>
						</tr>
    					@endforeach
    					<tr>
    						<td>{!!
    						link_to_route(
    							'entry.new',
    							'Create a new entry &#43;',
    							['todoList' => $todoList->id], ['class' => 'text-success font-weight-bolder'])
    							!!}
    						</td>
    						{!! str_repeat('<td></td>', 3) !!}
    					</tr>
    					</tbody>
    				</table>
            	</div>
            </div>
        </div>
    </div>
</div>
@endsection