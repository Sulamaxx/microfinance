@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <span class="header-title">{{ _lang('Center Details') }}</span>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>{{ _lang('Name') }}</td>
                            <td>{{ $branch->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ _lang('No') }}</td>
                            <td>{{ $branch->no }}</td>
                        </tr>
                        <tr>
                            <td>{{ _lang('Code') }}</td>
                            <td>{{ $branch->code }}</td>
                        </tr>
                        <tr>
                            <td>{{ _lang('Working Days') }}</td>
                            <td>
                                @foreach ($branch->weekdays as $day)
                                    {{ $day }}{{ $loop->last ? '' : ',' }}
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
