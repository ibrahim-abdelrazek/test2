@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3> Edit Partner Types </h3>
                    </div>

                    <div class="panel-body">

                        {!! Form::model($partnertype, ['route' => ['partnertypes.update', $partnertype->id], 'method' => 'patch']) !!}


                        @include('partnertypes.fields')

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
