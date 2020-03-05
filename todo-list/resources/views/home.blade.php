@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Your lists:</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					<ul class="mb-0">
    					@foreach ($user->lists as $list)
    						<li>{!! link_to_route('list.edit', $list->description, ['todoList' => $list->id]) !!}</li>
    					@endforeach
    					<li>{!!
    						link_to_route(
    							'list.new',
    							'Create a new list &#43;',
    							[], ['class' => 'text-success font-weight-bolder'])
    							!!}</li>
					</ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
