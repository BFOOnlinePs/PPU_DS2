<table class="table table-sm table-bordered">
    <tbody>
    @if($data->isEmpty())
        <tr>
            <td colspan="2" class="text-center">{{ __('translate.No available data') }}</td>
        </tr>
    @else
        @foreach($data as $key)
            <tr>
                <td><a href="{{ asset('public/storage/files/'. $key->file ) }}" target="_blank">{{ $key->file }}</a></td>
                <td>{{ $key->note }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
