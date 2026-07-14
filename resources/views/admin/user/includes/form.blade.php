<div class="card card-default"> 
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#general" role="tab" aria-selected="true">
                    General
                </a>
            </li>
                        
            @if(isset($data['row']))
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reset" role="tab"  aria-selected="false">
                    Reset password
                </a>
            </li>
            @endif
        </ul>
        <div class="tab-content" id="myTabContent" style="margin:15px;">
            <div id="general"  class=" tab-pane fade show active" role="tabpanel" >
                @include('admin.user.includes.general')
            </div>
            <div id="reset"  class=" tab-pane" role="tabpanel" >
                @include('admin.user.includes.reset-password ')
            </div><!-- /TAB 1 CONTENT -->



        </div>
    </div>
</div><br>
<div class="row">
    <div class="col-md-6">
        <button type="submit" name="submit"  class="btn btn-success"> {{ $button }} </button>
        <a type="button" href="{{ route($base_route.'.index') }}"
           class="btn btn-danger row-edit">
            {{ App::isLocale('en') ? 'Cancel' : 'रद्द गर्नुहोस्' }}
        </a>
    </div>
</div>