<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col" style="display:none;">id</th>
                <th scope="col">{{__('translate.Company Category')}}{{-- تصنيف الشركة --}}</th>
                <th scope="col">{{__('translate.Operations')}} {{--  العمليات --}}</th>
            </tr>
        </thead>
        <tbody>
            @if ($data->isEmpty())
                <tr>
                    <td colspan="2" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
                </tr>
                @else
                @foreach ($data as $key)
                    <tr>
                        <td>{{ $key->cc_name }}</td>
                        <td>
                            <button class="btn btn-info" onclick="editCompaniesCategories({{ $key }})"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
