@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <ul class="nav flex-column nav-tabs settings-tab" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#guarantor_details"><i
                            class="ti-user"></i>&nbsp;{{ _lang('Guarantor Details') }}</a></li>
                {{-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#account_overview"><i
                            class="ti-credit-card"></i>&nbsp;{{ _lang('Account Overview') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#transaction-history"><i
                            class="ti-view-list-alt"></i>{{ _lang('Transactions') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#guarantor_loans"><i
                            class="ti-agenda"></i>&nbsp;{{ _lang('Loans') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kyc_documents"><i
                            class="ti-files"></i>&nbsp;{{ _lang('KYC Documents') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#email"><i
                            class="ti-email"></i>&nbsp;{{ _lang('Send Email') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sms"><i
                            class="ti-comment-alt"></i>&nbsp;{{ _lang('Send SMS') }}</a></li> --}}
                <li class="nav-item"><a class="nav-link" href="{{ route('guarantor_managements.edit', $guarantor->id) }}"><i
                            class="ti-pencil-alt"></i>&nbsp;{{ _lang('Edit Guarantor Details') }}</a></li>
            </ul>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="tab-content">
                <div id="guarantor_details" class="tab-pane active">
                    <div class="card">
                        <div class="card-header">
                            <span class="header-title">{{ _lang('Guarantor Details') }}</span>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td>{{ _lang('Title') }}</td>
                                    <td>{{ $guarantor->title }}</td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('Full Name') }}</td>
                                    <td>{{ $guarantor->full_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('Name With Initial') }}</td>
                                    <td>{{ $guarantor->name_with_initial }}</td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('NIC') }}</td>
                                    <td>{{ $guarantor->nic }}</td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('Mobile 1') }}</td>
                                    <td>{{ $guarantor->mobile1 }}</td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('Mobile 2') }}</td>
                                    <td>{{ $guarantor->mobile2 }}</td>
                                </tr>

                                <tr>
                                    <td>{{ _lang('Address') }}</td>
                                    <td>{{ $guarantor->address1 . ', ' . $guarantor->address2 }}</td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('City') }}</td>
                                    <td>{{ $guarantor->city }}</td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('State') }}</td>
                                    <td>{{ $guarantor->state }}</td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('Zip') }}</td>
                                    <td>{{ $guarantor->zip }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="profile_picture text-center">
                                        <span class="font-weight-bold">Signature</span>
                                        <img src="{{ profile_picture($guarantor->photo) }}" class="thumb-image-md">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- <div id="account_overview" class="tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <span class="header-title">{{ _lang('Account Overview') }}</span>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">{{ _lang('Account Number') }}</th>
                                            <th class="text-nowrap">{{ _lang('Account Type') }}</th>
                                            <th>{{ _lang('Currency') }}</th>
                                            <th class="text-right">{{ _lang('Balance') }}</th>
                                            <th class="text-nowrap text-right">{{ _lang('Loan Guarantee') }}</th>
                                            <th class="text-nowrap text-right">{{ _lang('Current Balance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $accounts = get_account_details($guarantor->id);
                                        @endphp

                                        @if ($accounts && count($accounts) > 0)
                                            @foreach ($accounts as $account)
                                                <tr>
                                                    <td>{{ $account->account_number }}</td>
                                                    <td class="text-nowrap">{{ $account->savings_type->name }}</td>
                                                    <td>{{ $account->savings_type->currency->name }}</td>
                                                    <td class="text-nowrap text-right">
                                                        {{ decimalPlace($account->balance, currency($account->savings_type->currency->name)) }}
                                                    </td>
                                                    <td class="text-nowrap text-right">
                                                        {{ decimalPlace($account->blocked_amount, currency($account->savings_type->currency->name)) }}
                                                    </td>
                                                    <td class="text-nowrap text-right">
                                                        {{ decimalPlace($account->balance - $account->blocked_amount, currency($account->savings_type->currency->name)) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="transaction-history" class="tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <span class="header-title">{{ _lang('Transactions') }}</span>
                        </div>

                        <div class="card-body">
                            <table id="transactions_table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ _lang('Date') }}</th>
                                        <th>{{ _lang('guarantor') }}</th>
                                        <th>{{ _lang('Account Number') }}</th>
                                        <th>{{ _lang('Amount') }}</th>
                                        <th>{{ _lang('Debit/Credit') }}</th>
                                        <th>{{ _lang('Type') }}</th>
                                        <th>{{ _lang('Status') }}</th>
                                        <th class="text-center">{{ _lang('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!--End Transaction Table-->

                <div id="guarantor_loans" class="tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <span class="header-title">{{ _lang('Loans') }}</span>
                        </div>

                        <div class="card-body">
                            <table id="loans_table" class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>{{ _lang('Loan ID') }}</th>
                                        <th>{{ _lang('Loan Product') }}</th>
                                        <th class="text-right">{{ _lang('Applied Amount') }}</th>
                                        <th class="text-right">{{ _lang('Total Payable') }}</th>
                                        <th class="text-right">{{ _lang('Amount Paid') }}</th>
                                        <th class="text-right">{{ _lang('Due Amount') }}</th>
                                        <th>{{ _lang('Release Date') }}</th>
                                        <th>{{ _lang('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guarantor->loans != null && count($guarantor->loans) > 0 && $guarantor->loans as $loan)
                                        <tr>
                                            <td><a href="{{ route('loans.show', $loan->id) }}">{{ $loan->loan_id }}</a>
                                            </td>
                                            <td>{{ $loan->loan_product->name }}</td>
                                            <td class="text-right">
                                                {{ decimalPlace($loan->applied_amount, currency($loan->currency->name)) }}
                                            </td>
                                            <td class="text-right">
                                                {{ decimalPlace($loan->total_payable, currency($loan->currency->name)) }}
                                            </td>
                                            <td class="text-right">
                                                {{ decimalPlace($loan->total_paid, currency($loan->currency->name)) }}</td>
                                            <td class="text-right">
                                                {{ decimalPlace($loan->total_payable - $loan->total_paid, currency($loan->currency->name)) }}
                                            </td>
                                            <td>{{ $loan->release_date }}</td>
                                            <td>
                                                @if ($loan->status == 0)
                                                    {!! xss_clean(show_status(_lang('Pending'), 'warning')) !!}
                                                @elseif($loan->status == 1)
                                                    {!! xss_clean(show_status(_lang('Approved'), 'success')) !!}
                                                @elseif($loan->status == 2)
                                                    {!! xss_clean(show_status(_lang('Completed'), 'info')) !!}
                                                @elseif($loan->status == 3)
                                                    {!! xss_clean(show_status(_lang('Cancelled'), 'danger')) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="kyc_documents" class="tab-pane">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4 class="header-title">
                                {{ _lang('Documents of') . ' ' . $guarantor->first_name . ' ' . $guarantor->last_name }}</h4>
                            <a class="btn btn-primary btn-xs ml-auto ajax-modal"
                                data-title="{{ _lang('Add New Document') }}"
                                href="{{ route('guarantor_documents.create', $guarantor->id) }}"><i
                                    class="ti-plus"></i>&nbsp;{{ _lang('Add New') }}</a>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>{{ _lang('Document Name') }}</th>
                                        <th>{{ _lang('Document File') }}</th>
                                        <th>{{ _lang('Submitted At') }}</th>
                                        <th>{{ _lang('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guarantor->documents != null && count($guarantor->documents) > 0 && $guarantor->documents as $document)
                                        <tr>
                                            <td>{{ $document->name }}</td>
                                            <td><a target="_blank"
                                                    href="{{ asset('public/uploads/documents/' . $document->document) }}">{{ $document->document }}</a>
                                            </td>
                                            <td>{{ date('d M, Y H:i:s', strtotime($document->created_at)) }}</td>
                                            <td class="text-center">
                                                <span class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle btn-xs" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        {{ _lang('Action') }}
                                                    </button>
                                                    <form action="{{ route('guarantor_documents.destroy', $document->id) }}"
                                                        method="post">
                                                        {{ csrf_field() }}
                                                        <input name="_method" type="hidden" value="DELETE">

                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a href="{{ route('guarantor_documents.edit', $document->id) }}"
                                                                data-title="{{ _lang('Update Document') }}"
                                                                class="dropdown-item dropdown-edit ajax-modal"><i
                                                                    class="ti-pencil-alt"></i>&nbsp;{{ _lang('Edit') }}</a>
                                                            <button class="btn-remove dropdown-item" type="submit"><i
                                                                    class="ti-trash"></i>&nbsp;{{ _lang('Delete') }}</button>
                                                        </div>
                                                    </form>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!--End KYC Documents Tab-->

                <div id="email" class="tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <span class="header-title">{{ _lang('Send Email') }}</span>
                        </div>

                        <div class="card-body">
                            <form method="post" class="validate" autocomplete="off"
                                action="{{ route('guarantors.send_email') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('User Email') }}</label>
                                            <input type="email" class="form-control" name="user_email"
                                                value="{{ $guarantor->email }}" required="" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Subject') }}</label>
                                            <input type="text" class="form-control" name="subject"
                                                value="{{ old('subject') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Message') }}</label>
                                            <textarea class="form-control" rows="8" name="message" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block"><i
                                                    class="ti-check-box"></i>&nbsp;{{ _lang('Send') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!--End Send Email Tab-->

                <div id="sms" class="tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <span class="header-title">{{ _lang('Send SMS') }}</span>
                        </div>

                        <div class="card-body">
                            <form method="post" class="validate" autocomplete="off"
                                action="{{ route('guarantors.send_sms') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('User Mobile') }}</label>
                                            <input type="text" class="form-control" name="phone"
                                                value="{{ $guarantor->country_code . $guarantor->mobile }}" required=""
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Message') }}</label>
                                            <textarea class="form-control" name="message" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block"><i
                                                    class="ti-check-box"></i>&nbsp;{{ _lang('Send') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!--End Send SMS Tab--> --}}

            </div>
        </div>
    </div>
@endsection

@section('js-script')
    {{-- <script>
        (function($) {

            "use strict";

            $('#transactions_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('admin/guarantors/get_guarantor_transaction_data/' . $guarantor->id) }}',
                "columns": [{
                        data: 'trans_date',
                        name: 'trans_date'
                    },
                    {
                        data: 'guarantor.first_name',
                        name: 'guarantor.first_name'
                    },
                    {
                        data: 'account.account_number',
                        name: 'account.account_number'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'dr_cr',
                        name: 'dr_cr'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

            $('.nav-tabs a').on('shown.bs.tab', function(event) {
                var tab = $(event.target).attr("href");
                var url = "{{ route('guarantors.show', $guarantor->id) }}";
                history.pushState({}, null, url + "?tab=" + tab.substring(1));
            });

            @if (isset($_GET['tab']))
                $('.nav-tabs a[href="#{{ $_GET['tab'] }}"]').tab('show');
            @endif

            $("a[data-toggle=\"tab\"]").on("shown.bs.tab", function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

        })(jQuery);
    </script> --}}
@endsection
