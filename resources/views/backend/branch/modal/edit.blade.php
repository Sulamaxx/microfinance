<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('branches.update', $id) }}"
    enctype="multipart/form-data">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PATCH">

    <div class="row px-2">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Name') }}</label>
                <input type="text" class="form-control" name="name" value="{{ $branch->name }}" required>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('No') }}</label>
                <input type="text" class="form-control" name="no" value="{{ $branch->no }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Code') }}</label>
                <input type="text" class="form-control" name="code" value="{{ $branch->code }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Select Working days') }}</label><br>
                @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                    <label>
                        <input type="checkbox" {{ in_array($day, $branch->weekdays) ? 'checked' : '' }}
                            class="form-check-label" name="weekdays[]" value="{{ $day }}">
                        {{ $day }}
                    </label><br>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary "><i
                        class="ti-check-box"></i>&nbsp;{{ _lang('Update') }}</button>
            </div>
        </div>
    </div>
</form>
