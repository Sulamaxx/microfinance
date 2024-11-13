@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">{{ _lang('Create User') }}</h4>
                </div>
                <div class="card-body">
                    <form method="post" class="validate" autocomplete="off" action="{{ route('users.store') }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-form-label">{{ _lang('Name') }}</label>
                                    <div class="col-xl-9">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-form-label">{{ _lang('Email') }}</label>
                                    <div class="col-xl-9">
                                        <input type="text" class="form-control" name="email"
                                            value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-form-label">{{ _lang('Password') }}</label>
                                    <div class="col-xl-9">
                                        <input type="password" class="form-control" name="password" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-form-label">{{ _lang('User Type') }}</label>
                                    <div class="col-xl-9">
                                        <select class="form-control auto-select" data-selected="{{ old('user_type') }}"
                                            name="user_type" id="user_type" required>
                                            <option value="">{{ _lang('Select One') }}</option>
                                            {{-- <option value="admin">{{ _lang('Admin') }}</option> --}}
                                            <option value="user">{{ _lang('User') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-form-label">{{ _lang('User Role') }}</label>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="role_id" id="role_id" disabled>
                                            <option value="0">{{ _lang('Select One') }}</option>
                                            {{ create_option('roles', 'id', 'name') }}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-form-label">{{ _lang('Center') }}</label>
                                    <div class="col-xl-9">
                                        @if (auth()->user()->user_type == 'admin')
                                            <select class="form-control select2" name="branch_id">
                                                <option value="">
                                                    {{ get_option('default_branch_name', 'Main Branch') }}
                                                </option>
                                                {{ create_option('branches', 'id', 'name', auth()->user()->branch_id) }}
                                            </select>
                                        @else
                                            <select class="form-control" name="branch_id" disabled>
                                                <option value="">
                                                    {{ get_option('default_branch_name', 'Main Branch') }}
                                                </option>
                                                {{ create_option('branches', 'id', 'name', auth()->user()->branch_id) }}
                                            </select>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-form-label">{{ _lang('Status') }}</label>
                                    <div class="col-xl-9">
                                        <select class="form-control auto-select" data-selected="{{ old('status', 1) }}"
                                            name="status" required>
                                            <option value="">{{ _lang('Select One') }}</option>
                                            <option value="1">{{ _lang('Active') }}</option>
                                            <option value="0">{{ _lang('In Active') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-form-label">{{ _lang('Profile Picture') }}</label>
                                    <div class="col-xl-9">
                                        <input type="file" class="form-control dropify" name="profile_picture">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-xl-9 offset-xl-3">
                                        <button type="submit" class="btn btn-primary">{{ _lang('Create User') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('user_type').addEventListener('change', () => {
            var user_type = document.getElementById('user_type').value;
            if (user_type === 'user') {
                document.getElementById('role_id').disabled = false;
            } else {
                document.getElementById('role_id').disabled = true;
            }
        });
    </script>
@endsection
