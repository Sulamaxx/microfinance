{{-- @extends('layouts.app')

@section('content')
    <form method="post" class="validate" autocomplete="off" action="{{ route('members.update', $id) }}"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Update Member Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <input name="_method" type="hidden" value="PATCH">

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection --}}

@extends('layouts.app')

@section('content')
    <form method="post" class="validate" autocomplete="off" action="{{ route('members.update', $id) }}"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PATCH">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Update Member Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Title') }}</label>
                                    <select class="form-control select2" name="title" required>
                                        <option value="Mrs." {{ $member->title == 'Mrs.' ? 'selected' : '' }}>
                                            {{ _lang('Mrs.') }}</option>
                                        <option value="Miss." {{ $member->title == 'Miss.' ? 'selected' : '' }}>
                                            {{ _lang('Miss.') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Customer Id -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Customer Id') }}</label>
                                    <input type="text" class="form-control" name="customer_id"
                                        value="{{ old('customer_id', $member->customer_id) }}" required>
                                </div>
                            </div>

                            <!-- Civil Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Civil Status') }}</label>
                                    <select class="form-control select2" name="civil_status" required>
                                        <option value="Single" {{ $member->civil_status == 'Single' ? 'selected' : '' }}>
                                            {{ _lang('Single') }}</option>
                                        <option value="Married" {{ $member->civil_status == 'Married' ? 'selected' : '' }}>
                                            {{ _lang('Married') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Gender') }}</label>
                                    <select class="form-control select2" name="gender" required>
                                        <option value="Male" {{ $member->gender == 'Male' ? 'selected' : '' }}>
                                            {{ _lang('Male') }}</option>
                                        <option value="Female" {{ $member->gender == 'Female' ? 'selected' : '' }}>
                                            {{ _lang('Female') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Full Name') }}</label>
                                    <input type="text" class="form-control" name="full_name"
                                        value="{{ old('full_name', $member->full_name) }}" required>
                                </div>
                            </div>

                            <!-- Name With Initial -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Name With Initial') }}</label>
                                    <input type="text" class="form-control" name="name_with_initial"
                                        value="{{ old('name_with_initial', $member->name_with_initial) }}" required>
                                </div>
                            </div>

                            <!-- NIC No -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('NIC No') }}</label>
                                    <input type="text" class="form-control" name="nic"
                                        value="{{ old('nic', $member->nic) }}" required>
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Date of Birth') }}</label>
                                    <input type="date" class="form-control" name="dob"
                                        value="{{ old('dob', $member->dob) }}" required>
                                </div>
                            </div>

                            <!-- Contact Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Contact Number') }}</label>
                                    <input type="number" class="form-control" name="contact_number"
                                        value="{{ old('contact_number', $member->contact_number) }}" required>
                                </div>
                            </div>

                            <!-- Mobile Number 1 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 1') }}</label>
                                    <input type="number" class="form-control" name="mobile1"
                                        value="{{ old('mobile1', $member->mobile1) }}" required>
                                </div>
                            </div>

                            <!-- Mobile Number 2 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 2') }}</label>
                                    <input type="number" class="form-control" name="mobile2"
                                        value="{{ old('mobile2', $member->mobile2) }}">
                                </div>
                            </div>

                            <!-- Branch -->
                            @if (auth()->user()->user_type == 'admin')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Branch') }}</label>
                                        <select class="form-control select2" name="branch_id">
                                            <option value="">{{ get_option('default_branch_name', 'Main Branch') }}
                                            </option>
                                            {{ create_option('branches', 'id', 'name', $member->branch_id) }}
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

                            <!-- Address Line 1 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 1') }}</label>
                                    <input type="text" class="form-control" name="address1"
                                        value="{{ old('address1', $member->address1) }}" required>
                                </div>
                            </div>

                            <!-- Address Line 2 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 2') }}</label>
                                    <input type="text" class="form-control" name="address2"
                                        value="{{ old('address2', $member->address2) }}" required>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('City') }}</label>
                                    <input type="text" class="form-control" name="city"
                                        value="{{ old('city', $member->city) }}">
                                </div>
                            </div>

                            <!-- State -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('State') }}</label>
                                    <input type="text" class="form-control" name="state"
                                        value="{{ old('state', $member->state) }}">
                                </div>
                            </div>

                            <!-- Zip -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Zip') }}</label>
                                    <input type="text" class="form-control" name="zip"
                                        value="{{ old('zip', $member->zip) }}">
                                </div>
                            </div>

                            <!-- Signature Photo -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Signature Photo') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="photo">
                                    @if ($member->photo != null)
                                        <img src="{{ asset('uploads/profile/' . $member->photo) }}" alt="Member Photo"
                                            class="img-thumbnail mt-2" width="150">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sponsor Details -->
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Sponsor Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Full Name') }}</label>
                                    <input type="text" class="form-control" name="sponser_full_name"
                                        value="{{ old('sponser_full_name', $member->sponsor->full_name) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('NIC No') }}</label>
                                    <input type="text" class="form-control" name="sponser_nic"
                                        value="{{ old('sponser_nic', $member->sponsor->nic) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 1') }}</label>
                                    <input type="number" class="form-control" name="sponser_mobile1"
                                        value="{{ old('sponser_mobile1', $member->sponsor->mobile1) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 2') }}</label>
                                    <input type="number" class="form-control" name="sponser_mobile2"
                                        value="{{ old('sponser_mobile2', $member->sponsor->mobile2) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 1') }}</label>
                                    <input type="text" class="form-control" name="sponser_address1"
                                        value="{{ old('sponser_address1', $member->sponsor->address1) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 2') }}</label>
                                    <input type="text" class="form-control" name="sponser_address2"
                                        value="{{ old('sponser_address2', $member->sponsor->address2) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('City') }}</label>
                                    <input type="text" class="form-control" name="sponser_city"
                                        value="{{ old('sponser_city', $member->sponsor->city) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('State') }}</label>
                                    <input type="text" class="form-control" name="sponser_state"
                                        value="{{ old('sponser_state', $member->sponsor->state) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Zip') }}</label>
                                    <input type="text" class="form-control" name="sponser_zip"
                                        value="{{ old('sponser_zip', $member->sponsor->zip) }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Signature Photo') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="sponser_photo">
                                    @if ($member->sponsor->photo != null)
                                        <img src="{{ asset('uploads/profile/' . $member->sponsor->photo) }}"
                                            alt="Sponser Photo" class="img-thumbnail mt-2" width="150">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Document Image 1') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="sponser_doc_image1">
                                    @if ($member->sponsor->doc_image1 != null)
                                        <img src="{{ asset('uploads/profile/' . $member->sponsor->doc_image1) }}"
                                            alt="Sponser Document Photo" class="img-thumbnail mt-2" width="150">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Document Image 2') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="sponser_doc_image2">
                                    @if ($member->sponsor->doc_image2 != null)
                                        <img src="{{ asset('uploads/profile/' . $member->sponsor->doc_image2) }}"
                                            alt="Sponser Document Photo" class="img-thumbnail mt-2" width="150">
                                    @endif
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
