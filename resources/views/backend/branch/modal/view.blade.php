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
