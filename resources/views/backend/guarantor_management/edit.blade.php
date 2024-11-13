@extends('layouts.app')

@section('content')
    <form method="post" class="validate" autocomplete="off" action="{{ route('guarantor_managements.update', $id) }}"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PATCH">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Update Guarantor Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Title') }}</label>
                                    <select class="form-control select2" name="title" required>
                                        <option value="Mrs." {{ $guarantor->title == 'Mrs.' ? 'selected' : '' }}>
                                            {{ _lang('Mrs.') }}</option>
                                        <option value="Miss." {{ $guarantor->title == 'Miss.' ? 'selected' : '' }}>
                                            {{ _lang('Miss.') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Full Name') }}</label>
                                    <input type="text" class="form-control" name="full_name"
                                        value="{{ old('full_name', $guarantor->full_name) }}" required>
                                </div>
                            </div>

                            <!-- Name With Initial -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Name With Initial') }}</label>
                                    <input type="text" class="form-control" name="name_with_initial"
                                        value="{{ old('name_with_initial', $guarantor->name_with_initial) }}" required>
                                </div>
                            </div>

                            <!-- NIC No -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('NIC No') }}</label>
                                    <input type="text" class="form-control" name="nic"
                                        value="{{ old('nic', $guarantor->nic) }}" required>
                                </div>
                            </div>

                            <!-- Mobile Number 1 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 1') }}</label>
                                    <input type="number" class="form-control" name="mobile1"
                                        value="{{ old('mobile1', $guarantor->mobile1) }}" required>
                                </div>
                            </div>

                            <!-- Mobile Number 2 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Mobile Number 2') }}</label>
                                    <input type="number" class="form-control" name="mobile2"
                                        value="{{ old('mobile2', $guarantor->mobile2) }}">
                                </div>
                            </div>

                            <!-- Address Line 1 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 1') }}</label>
                                    <input type="text" class="form-control" name="address1"
                                        value="{{ old('address1', $guarantor->address1) }}" required>
                                </div>
                            </div>

                            <!-- Address Line 2 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Address Line 2') }}</label>
                                    <input type="text" class="form-control" name="address2"
                                        value="{{ old('address2', $guarantor->address2) }}" required>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('City') }}</label>
                                    <input type="text" class="form-control" name="city"
                                        value="{{ old('city', $guarantor->city) }}">
                                </div>
                            </div>

                            <!-- State -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('State') }}</label>
                                    <input type="text" class="form-control" name="state"
                                        value="{{ old('state', $guarantor->state) }}">
                                </div>
                            </div>

                            <!-- Zip -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Zip') }}</label>
                                    <input type="text" class="form-control" name="zip"
                                        value="{{ old('zip', $guarantor->zip) }}">
                                </div>
                            </div>

                            <!-- Signature Photo -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Signature Photo') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="photo">
                                    @if ($guarantor->photo != null)
                                        <img src="{{ asset('uploads/profile/' . $guarantor->photo) }}"
                                            alt="guarantor Photo" class="img-thumbnail mt-2" width="150">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Document Image') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="doc_image">
                                    @if ($guarantor->doc_image != null)
                                        <img src="{{ asset('uploads/profile/' . $guarantor->doc_image) }}"
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
