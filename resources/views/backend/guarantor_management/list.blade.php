@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card no-export">
                <div class="card-header d-flex align-items-center">
                    <span class="panel-title">{{ _lang('Guarantor List') }}</span>

                    <div class="ml-auto">
                        <a class="btn btn-primary btn-xs" href="{{ route('guarantor_managements.create') }}"><i
                                class="ti-plus mr-1"></i>{{ _lang('Add New') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="guarantors_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ _lang('NIC') }}</th>
                                <th>{{ _lang('Title') }}</th>
                                <th>{{ _lang('Full Name') }}</th>
                                <th>{{ _lang('Name With Initial') }}</th>
                                <th>{{ _lang('Mobile No 1') }}</th>
                                <th>{{ _lang('Mobile No 2') }}</th>
                                <th>{{ _lang('Status') }}</th>
                                <th class="text-center">{{ _lang('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-script')
    <script>
        (function($) {
            "use strict";

            $('#guarantors_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('admin/guarantor_managements/get_table_data') }}',
                "columns": [{
                        data: 'nic',
                        name: 'nic'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'name_with_initial',
                        name: 'name_with_initial'
                    },
                    {
                        data: 'mobile1',
                        name: 'mobile1'
                    },
                    {
                        data: 'mobile2',
                        name: 'mobile2'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            if (data == 1) {
                                return '<span class="badge badge-success">Active</span>';
                            } else {
                                return '<span class="badge badge-danger">Inactive</span>';
                            }
                        }
                    },
                    {
                        data: "action",
                        name: "action"
                    },
                ],
                responsive: true,
                "bStateSave": true,
                "bAutoWidth": false,
                "ordering": false,
                "language": {
                    "decimal": "",
                    "emptyTable": "{{ _lang('No Data Found') }}",
                    "info": "{{ _lang('Showing') }} _START_ {{ _lang('to') }} _END_ {{ _lang('of') }} _TOTAL_ {{ _lang('Entries') }}",
                    "infoEmpty": "{{ _lang('Showing 0 To 0 Of 0 Entries') }}",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{ _lang('Show') }} _MENU_ {{ _lang('Entries') }}",
                    "loadingRecords": "{{ _lang('Loading...') }}",
                    "processing": "{{ _lang('Processing...') }}",
                    "search": "{{ _lang('Search') }}",
                    "zeroRecords": "{{ _lang('No matching records found') }}",
                    "paginate": {
                        "first": "{{ _lang('First') }}",
                        "last": "{{ _lang('Last') }}",
                        "previous": "<i class='ti-angle-left'></i>",
                        "next": "<i class='ti-angle-right'></i>",
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-bordered");
                }
            });

        })(jQuery);
    </script>
@endsection