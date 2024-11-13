@extends('layouts.app')

@section('content')
    <form method="post" class="validate" autocomplete="off" action="{{ route('members.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Add New Member') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Title') }}</label>
                                    <select class="form-control select2" name="title" required>
                                        <option value="Mrs.">{{ _lang('Mrs.') }}</option>
                                        <option value="Miss.">{{ _lang('Miss.') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Customer Id') }}</label>
                                    <input type="text" class="form-control" name="customer_id"
                                        value="{{ old('customer_id') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Civil Status') }}</label>
                                    <select class="form-control select2" name="civil_status" required>
                                        <option value="Single">{{ _lang('Single') }}</option>
                                        <option value="Married">{{ _lang('Married') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Gender') }}</label>
                                    <select class="form-control select2" name="gender" required>
                                        <option value="Male">{{ _lang('Male') }}</option>
                                        <option value="Female">{{ _lang('Female') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Full Name') }}</label>
                                    <input type="text" class="form-control" name="full_name"
                                        value="{{ old('full_name') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Name With Initial') }}</label>
                                    <input type="text" class="form-control" name="name_with_initial"
                                        value="{{ old('name_with_initial"') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('NIC No') }}</label>
                                    <input type="text" class="form-control" name="nic" value="{{ old('nic') }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Date of Birth') }}</label>
                                    <input type="date" class="form-control" name="dob" value="{{ old('dob') }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Contact Number') }}</label>
                                    <input type="number" class="form-control" name="contact_number"
                                        value="{{ old('contact_number') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 1') }}</label>
                                    <input type="number" class="form-control" name="mobile1" value="{{ old('mobile1') }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 2') }}</label>
                                    <input type="number" class="form-control" name="mobile2" value="{{ old('mobile2') }}">
                                </div>
                            </div>

                            @if (auth()->user()->user_type == 'admin')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Branch') }}</label>
                                        <select class="form-control select2" name="branch_id">
                                            <option value="">{{ get_option('default_branch_name', 'Main Branch') }}
                                            </option>
                                            {{ create_option('branches', 'id', 'name', auth()->user()->branch_id) }}
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Branch') }}</label>
                                        <select class="form-control" name="branch_id" disabled>
                                            <option value="">{{ get_option('default_branch_name', 'Main Branch') }}
                                            </option>
                                            {{ create_option('branches', 'id', 'name', auth()->user()->branch_id) }}
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 1') }}</label>
                                    <input type="text" class="form-control" name="address1"
                                        value="{{ old('address1') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 2') }}</label>
                                    <input type="text" class="form-control" name="address2"
                                        value="{{ old('address2') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('City') }}</label>
                                    <input type="text" class="form-control" name="city"
                                        value="{{ old('city') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('State') }}</label>
                                    <input type="text" class="form-control" name="state"
                                        value="{{ old('state') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Zip') }}</label>
                                    <input type="text" class="form-control" name="zip"
                                        value="{{ old('zip') }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Signature Photo') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="photo">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Sponser Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Full Name') }}</label>
                                    <input type="text" class="form-control" name="sponser_full_name"
                                        value="{{ old('sponser_full_name') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('NIC No') }}</label>
                                    <input type="text" class="form-control" name="sponser_nic"
                                        value="{{ old('sponser_nic') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 1') }}</label>
                                    <input type="number" class="form-control" name="sponser_mobile1"
                                        value="{{ old('sponser_mobile1') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 2') }}</label>
                                    <input type="number" class="form-control" name="sponser_mobile2"
                                        value="{{ old('sponser_mobile2') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 1') }}</label>
                                    <input type="text" class="form-control" name="sponser_address1"
                                        value="{{ old('sponser_address1') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 2') }}</label>
                                    <input type="text" class="form-control" name="sponser_address2"
                                        value="{{ old('sponser_address2') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('City') }}</label>
                                    <input type="text" class="form-control" name="sponser_city"
                                        value="{{ old('sponser_city') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('State') }}</label>
                                    <input type="text" class="form-control" name="sponser_state"
                                        value="{{ old('sponser_state') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Zip') }}</label>
                                    <input type="text" class="form-control" name="sponser_zip"
                                        value="{{ old('sponser_zip') }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Signature Photo') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="sponser_photo">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Document Image 1') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="sponser_doc_image1">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Document Image 2') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="sponser_doc_image2">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="ti-check-box"></i>&nbsp;{{ _lang('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </form>
    <script>
        function validateImage(input) {
            if (input.files.length > 1) {
                alert("Please select only one image.");
                input.value = "";
            }
        }
    </script>
@endsection
