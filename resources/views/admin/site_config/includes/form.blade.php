<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @php $percentage = 100/count(config('custom.setting_tabs')); @endphp
            @foreach(config('custom.setting_tabs') as $key => $name)
            <li class="nav-item" style="width:{{$percentage}}%; text-align:center;">
                <a class="nav-link {{ $data['requests']['form-name'] == $key? 'active' : ''}}"  id="{{$key}}"  href="{{ route($base_route.'.edit', ['form-name'=>$key]) }}" aria-controls="{{$key}}" aria-selected="true">
                    {{ $name }}
                </a>
            </li>
            @endforeach
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active p-4 " id="{{ $data['requests']['form-name'] }}" role="tabpanel" aria-labelledby="{{$data['requests']['form-name']}}">
                @include('admin.site_config.forms.'.$data['requests']['form-name'])
            </div>
        </div>
    </div>

</div>