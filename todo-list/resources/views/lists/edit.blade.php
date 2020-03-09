@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $todoList->description ?: ' a new todo list'}}</div>

                <div class="card-body">

                    {!! Form::model($todoList, array('route' => array('list.update'))) !!}
                        {!! Form::hidden('user_id', $todoList->exists? $todoList->user_id : $user->id) !!}
                        {!! Form::hidden('id') !!}
                        {!! Form::label('description', 'This is your list\'s name:') !!}
                        {!! Form::text('description', null, ['class' => 'w-100 form-control']) !!}
                        {!! Form::submit('Save', ['class' => 'btn btn-primary mt-2 col-3']) !!}
                        {!! Form::button('Delete', ['name' => 'delete', 'class' => 'btn btn-danger mt-2 col-3']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
			@if($todoList->exists)
            <div class="card mt-3">
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
        							'entry.edit', $entry->description,
        							['todoList' => $todoList->id, 'todoEntry' => $entry->id]) !!}</td>
        						<td>{{ $entry->priority }}</td>
        						<td data-order="{{ strtotime($entry->due_date) }}">
        							{{ strftime("%b %d, %Y", strtotime($entry->due_date)) }}</td>
        						<td>{{ $entry->getValidStatuses()[$entry->status] }}</td>
    						</tr>
        					@endforeach
    					</tbody>
    					<tfoot>
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
    					</tfoot>
    				</table>
            	</div>
            </div>
        	@endif
			{!! link_to_route('home', 'Home', null, ['class' => 'btn btn-primary mt-4 ml-3 col-3']) !!}
        </div>
    </div>
</div>
@endsection