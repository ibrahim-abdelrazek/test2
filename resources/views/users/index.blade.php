@extends('layouts.app')
@push('customcss')
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/jquery-confirm/jquery-confirm.min.css') }}"> <!-- original -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/libs/jquery-confirm/jquery.confirm.min.css') }}"> <!-- original -->
    <!-- customization -->
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/flexdatalist/jquery.flexdatalist.min.css')}}"> <!-- original -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/libs/flexdatalist/jquery.flexdatalist.min.css')}}"> <!-- customization -->

    <link rel="stylesheet" type="text/css" href="{{ asset('libs/datatables-net/media/css/dataTables.bootstrap4.min.css')}}"> <!-- original -->
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/datatables-net/extensions/responsive/js/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/datatables-net/extensions/buttons/css/buttons.bootstrap4.min.css')}}"> <!-- original -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/libs/datatables-net/datatables.min.css')}}"> <!-- customization -->

@endpush
@section('content')
    <div class="ks-page-header">
        <section class="ks-title">
            <h3>Users</h3>
            <a href="{{ route('users.create') }} " class="pull-right btn btn-success create"> Create User</a>
        </section>
    </div>
    <div class="ks-page-content">
        <div class="ks-page-content-body">

            <div class="container-fluid">
                @include('users.table')
            </div>
        </div>
    </div>

@endsection
