  <table id="user-datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th rowspan="1" colspan="1">Image</th>
                    <th rowspan="1" colspan="1">Name</th>
                    <th rowspan="1" colspan="1">Email</th>
                    <th rowspan="1" colspan="1">User Group</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isCallCenter())
                        <th>Partner</th>
                    @endif
                    <th rowspan="1" colspan="1">Actions</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th rowspan="1" colspan="1">Image</th>
                    <th rowspan="1" colspan="1">Name</th>
                    <th rowspan="1" colspan="1">Email</th>
                    <th rowspan="1" colspan="1">User Group</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isCallCenter())
                        <th>Partner</th>
                    @endif
                    <th rowspan="1" colspan="1">Actions</th>
                </tr>
                </tfoot>
                <tbody>
                @php($i=1)
        @foreach($users as $user)
            <tr role="row" class="{{ $i%2==0 ? 'even' : 'odd' }}">
                <td><img style="width:100px" src="{{url($user->avatar)}}"></td>
                <td><span data-toggle="tooltip" data-placement="top" title="{!! $user->first_name . ' ' . $user->last_name !!}" data-original-title="{!! $user->first_name . ' ' . $user->last_name !!}">{!! substr($user->first_name . ' ' . $user->last_name,0,20) !!}</span></td>
                <td><span data-toggle="tooltip" data-placement="top" title="{!! $user->email !!}" data-original-title="{!! $user->email !!}">{!! strlen($user->email) > 20 ? substr($user->email,0,20) . '...' : $user->email !!}</span></td>
                <td>{!! $user->userGroup->group_name !!}</td>
                @if(Auth::user()->isAdmin() || Auth::user()->isCallCenter())
                    <td>{!! \App\Partner::where('id',$user->partner_id)->value('first_name') . ' '  . \App\Partner::where('id',$user->partner_id)->value('last_name')!!}</td>
                @endif

                    <td>

                    <div class='btn-group'>
                        <a href="{!! route('users.show', [$user->id]) !!}" class='btn btn-default btn-xs'>Show</a>
                        <a href="{!! route('users.edit', [$user->id]) !!}" class='btn btn-default btn-xs'>Edit</a>
                        {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!} {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
            @php($i++)
        @endforeach

                </tbody>
            </table>
        
@push('customjs')
    <script src="{{ asset('libs/datatables-net/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/media/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('libs/datatables-net/extensions/buttons/js/buttons.colVis.min.js') }}"></script>
    <script type="application/javascript">
        (function ($) {
            $(document).ready(function () {
                var table = $('#user-datatable').DataTable({
                    responsive: true,
                    columnDefs: [
                        { width: '20%', targets: 3 }
                    ],
                    buttons: [
                        {
                            extend: 'copyHtml5',
                            exportOptions:{
                                columns: [1,2,3,4,5]
                            }
                        },
                        {
                            extend : 'excelHtml5',
                            exportOptions:{
                                columns: [1,2,3,4,5]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            exportOptions:{
                                columns: [1,2,3,4,5]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions:{
                                columns: [1,2,3,4,5]
                            }
                        }

                    ],

                    initComplete: function () {
                        $('.dataTables_wrapper select').select2({
                            minimumResultsForSearch: Infinity
                        });
                    }
                });

                table.buttons().container().appendTo('#user-datatable_wrapper .col-md-6:eq(0)');
                table.columns.adjust().draw();

            });
        })(jQuery);
    </script>
@endpush