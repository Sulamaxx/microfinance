@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <span class="panel-title">{{ _lang('Loan Calculator') }}</span>
                </div>
                <div class="card-body">
                    <form method="post" class="validate" autocomplete="off" action="{{ route('loans.calculate') }}">
                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Customer Name') }}</label>
                                    <input type="text" class="form-control" name="customer_name"
                                        value="{{ old('customer_name', $customer_name) }}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Apply Amount') }}</label>
                                    <input type="text" class="form-control float-field" name="apply_amount"
                                        value="{{ old('apply_amount', $apply_amount) }}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Interest Rate') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control float-field" name="interest_rate"
                                            value="{{ old('interest_rate', $interest_rate) }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Interest Type') }}</label>
                                    <select class="form-control auto-select"
                                        data-selected="{{ old('interest_type', $interest_type) }}" name="interest_type"
                                        id="interest_type" required>
                                        <option value="">{{ _lang('Select One') }}</option>
                                        <option value="monthly">{{ _lang('Month') }}</option>
                                        <option value="yearly">{{ _lang('Year') }}</option>cls
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Term') }}</label>
                                    <input type="number" class="form-control" name="term"
                                        value="{{ old('term', $term) }}" id="term" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Term Period') }}</label>
                                    <select class="form-control auto-select"
                                        data-selected="{{ old('term_period', $term_period) }}" name="term_period"
                                        id="term_period" required>
                                        <option value="">{{ _lang('Select One') }}</option>
                                        <option value="+1 week">{{ _lang('Week') }}</option>
                                        <option value="+1 month">{{ _lang('Month') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('First Payment date') }}</label>
                                    <input type="text" class="form-control datepicker" name="first_payment_date"
                                        value="{{ old('first_payment_date', $first_payment_date) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block"
                                        style="margin-top: 33px;">{{ _lang('Calculate') }}</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <a href="{{ route('loans.admin_calculator') }}" class="btn btn-danger btn-block"
                                        style="margin-top: 33px;">
                                        {{ _lang('Clear') }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>

                    @if (isset($table_data))
                        <a href="{{ route('loans.pdf-download') }}" class="btn btn-primary">
                            Download PDF
                        </a>

                        <h5 class="mt-4 text-center"><b>{{ _lang('Payable Amount') }}:
                                {{ decimalPlace(floor($payable_amount)) }}</b></h5>

                        <div class="table-responsive mt-5">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ _lang('No') }}</th>
                                        <th>{{ _lang('Date') }}</th>
                                        <th class="text-right">{{ _lang('Installment') }}</th>
                                        <th class="text-right">{{ _lang('Capital') }}</th>
                                        <th class="text-right">{{ _lang('Interest') }}</th>
                                        <th class="text-right">{{ _lang('Paid') }}</th>
                                        <th class="text-right">{{ _lang('Balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($table_data as $td)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d/m/Y', strtotime($td['date'])) }}</td>
                                            <td class="text-right">{{ decimalPlace($td['principle_amount']) }}</td>
                                            <td class="text-right">{{ decimalPlace($td['capital']) }}</td>
                                            <td class="text-right">{{ decimalPlace($td['interest']) }}</td>
                                            <td class="text-right">{{ decimalPlace($td['amount_to_pay']) }}</td>
                                            <td class="text-right">{{ decimalPlace($td['balance']) }}</td>
                                        </tr>
                                    @endforeach

                                    @foreach ($table_data_total as $td)
                                        <tr class="font-weight-bold">
                                            <td>Total</td>
                                            <td></td>
                                            <td class="text-right">{{ decimalPlace($td['principle_amount_total']) }}</td>
                                            {{-- <td class="text-right">{{ decimalPlace(floor($td['capital_total'])) }}</td>
                                            <td class="text-right">{{ decimalPlace(floor($td['interest_total'])) }}</td>
                                            <td class="text-right">{{ decimalPlace(floor($td['amount_to_pay_total'])) }}
                                            </td> --}}
                                            <td class="text-right">{{ decimalPlace($td['capital_total']) }}</td>
                                            <td class="text-right">{{ decimalPlace($td['interest_total']) }}</td>
                                            <td class="text-right">{{ decimalPlace($td['amount_to_pay_total']) }}
                                            </td>
                                            <td class="text-right">{{ decimalPlace(0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection


@section('js-script')
    <script>
        (function($) {
            "use strict";

            $(document).on('change', '#interest_type', function() {
                if ($(this).val() == 'one_time') {
                    $("#term").val(1);
                    $("#term_period").val('+1 day');
                    $("#term").prop('readonly', true);
                    $("#term_period").prop('disabled', true);
                } else {
                    $("#term").prop('readonly', false);
                    $("#term_period").prop('disabled', false);
                }
            });

        })(jQuery);
    </script>
@endsection
