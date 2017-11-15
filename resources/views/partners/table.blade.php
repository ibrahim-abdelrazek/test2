<div class="ks-nav-body">
    <div class="ks-nav-body-wrapper">
        <div class="container-fluid">
            <table id="partners-datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th rowspan="1" colspan="1">Name</th>
                    <th rowspan="1" colspan="1">Location</th>
                    <th rowspan="1" colspan="1">Partner Type</th>
                    <th rowspan="1" colspan="1">User Name</th>
                    <th rowspan="1" colspan="1">Email</th>
                    <th rowspan="1" colspan="1">Actions</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th rowspan="1" colspan="1">Name</th>
                    <th rowspan="1" colspan="1">Location</th>
                    <th rowspan="1" colspan="1">Partner Type</th>
                    <th rowspan="1" colspan="1">User Name</th>
                    <th rowspan="1" colspan="1">Email</th>
                    <th rowspan="1" colspan="1">Actions</th>
                </tr>
                </tfoot>
                <tbody>
                @php($i=1)
                @foreach($partners as $partner)
                    <tr role="row" class="{{ $i%2==0 ? 'even' : 'odd' }}">
                        <td class="sorting_1">{{$partner->name}}</td>
                        <td>{{ $partner->location }}</td>
                        <td>{{ $partner->partnerType->name }}</td>
                        <td> {{ $partner->user->username  }}</td>
                        <td>{{  $partner->user->email }}</td>


                        <td>
                            {!! Form::open(['route' => ['partners.destroy', $partner->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{!! route('partners.show', [$partner->id]) !!}" class='btn btn-default btn-xs'>Show</a>
                                <a href="{!! route('partners.edit', [$partner->id]) !!}" class='btn btn-default btn-xs'>Edit</a>
                                {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @php($i++)
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>

@push('customjs')
    <script src="{{ asset('libs/datatables-net/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/media/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script type="application/javascript">
        (function ($) {
            $(document).ready(function () {
                var table = $('#partners-datatable').DataTable({
                    lengthChange: false,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5',
                        'colvis'
                    ],
                    initComplete: function () {
                        $('.dataTables_wrapper select').select2({
                            minimumResultsForSearch: Infinity
                        });
                    }
                });

                table.buttons().container().appendTo('#partners-datatable_wrapper .col-md-6:eq(0)');
                $('#partners-datatable_filter').addClass('pull-right');
                $('#partners-datatable_paginate').addClass('pull-right');
            });
        })(jQuery);
    </script>
@endpush