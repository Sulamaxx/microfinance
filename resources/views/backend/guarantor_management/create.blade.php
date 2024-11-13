@extends('layouts.app')

@section('content')
    <form method="post" class="validate" autocomplete="off" action="{{ route('guarantor_managements.store') }}"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Add New Guarantor') }}</h4>
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
                                    <input type="text" class="form-control" name="city" value="{{ old('city') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('State') }}</label>
                                    <input type="text" class="form-control" name="state" value="{{ old('state') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Zip') }}</label>
                                    <input type="text" class="form-control" name="zip" value="{{ old('zip') }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Signature Photo') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="photo">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Document Image') }}</label>
                                    <input type="file" accept="image/*" onchange="validateImage(this)"
                                        class="form-control dropify" name="doc_image">
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
