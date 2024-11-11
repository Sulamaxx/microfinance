@extends('layouts.app')

@section('content')
    {{-- Dashboad Admin --}}
    <h1>Dashboad Admin</h1>
@endsection

@section('js-script')
    <script src="{{ asset('public/backend/plugins/chartJs/chart.min.js') }}"></script>
    <script src="{{ asset('public/backend/assets/js/dashboard.js?v=1.1') }}"></script>
@endsection
