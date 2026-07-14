@if(!Auth::user())
<div class="row">

    <!-- Feedback Box -->
    <div class="col-md-2 col-sm-6 p-1">

        <!-- BOX -->
        <div class="box default"><!-- default, danger, warning, info, success -->
            <a href="{{route('grievance.grievances.index') }}" class="badge p-4 badge-primary " style="width:100%">
                <div class="box-title"><!-- add .noborder class if box-body is removed -->
                    <i class="h5 fi fi-database"></i>
                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['grievance_statistics']['total'] + $data['raw_grievance_statistics']['total'] }} Total Grievance
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['grievance_statistics']['total'] + $data['raw_grievance_statistics']['total']) }} कुल प्राप्त गुनासो 
                    </h4>
                    @endif
                    <!-- <small class="block">654 New Grievance this month</small> -->
                </div>
            </a>
        </div>
        <!-- /BOX -->

    </div>

    <!-- Orders Box -->
    <div class="col-md-2 col-sm-6 p-1">
        
        <!-- BOX -->
        <div class="box info"><!-- default, danger, warning, info, success -->
            <a href="{{route('grievance.grievances.index',['filter_status'=>'registered']) }}" class="badge p-4  badge-info " style="width:100%">
                <i class="h5 fi fi-pencil"></i>
                <div class="box-title"><!-- add .noborder class if box-body is removed -->

                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['grievance_statistics']['total'] }} Registered 
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['grievance_statistics']['total']) }} कुल दर्ता भएको 
                    </h4>
                    @endif
                    <!-- <small class="block">18 Greivance are resolved in this month</small> -->
                </div>
            </a>
        </div>
    <!-- /BOX -->
    </div>

    <!-- Profit Box -->
    <div class="col-md-2 col-sm-6 p-1">

        <!-- BOX -->
        <div class="box success"><!-- default, danger, warning, info, success -->
            <a href="{{route('grievance.grievances.index',['filter_status'=>'closed']) }}" class="badge p-4  badge-success " style="width:100%">
                <div class="box-title"><!-- add .noborder class if box-body is removed -->
                    <i class="h5 fi fi-check"></i>
                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['grievance_statistics']['closed'] }} Resolved 
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['grievance_statistics']['closed']) }} फर्छ्यौट भएको 
                    </h4>
                    @endif
                    <!-- <small class="block">3 Grievances are in progress in this month</small> -->
                </div>
            </a>
        </div>
        <!-- /BOX -->

    </div>

    
    <!-- Feedback Box -->
    <div class="col-md-2 col-sm-6 p-1">

        <!-- BOX -->
        <div class="box warning"><!-- default, danger, warning, info, success -->
            <a href="{{route('grievance.grievances.index',['filter_status'=>'in_progress']) }}" class="badge p-4  badge-warning " style="width:100%">
                <div class="box-title"><!-- add .noborder class if box-body is removed -->
                    
                    <i class=" h5 fi fi-circle-spin"></i>
                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['grievance_statistics']['inprogress'] }} In Process 
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['grievance_statistics']['inprogress']) }} प्रकृयामा रहेको 
                    </h4>
                    @endif
                    <!-- <small class="block">654 New Grievance this month</small> -->
                </div>
            </a>
        </div>
        <!-- /BOX -->

    </div>

    <!-- Profit Box -->
    <div class="col-md-2 col-sm-6 p-1">

        <!-- BOX -->
        <div class="box default"><!-- default, danger, warning, info, success -->
            <a href="{{route('grievance.raw_grievances.index',['filter_status'=>'seen']) }}" class="badge badge-soft p-4  badge-success " style="width:100%">
                <div class="box-title"><!-- add .noborder class if box-body is removed -->
                    <i class="h5 fi fi-eye"></i>
                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['raw_grievance_statistics']['seen'] }} Seen
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['raw_grievance_statistics']['seen']) }} हेरिएको 
                    </h4>
                    @endif


                    <!-- <small class="block">3 Grievances are in progress in this month</small> -->
                </div>
            </a>
        </div>
        <!-- /BOX -->

    </div>

    <!-- Orders Box -->
    <div class="col-md-2 col-sm-6 p-1">

        <!-- BOX -->
        <div class="box danger"><!-- default, danger, warning, info, success -->
            <a href="{{route('grievance.raw_grievances.index',['filter_status'=>'new']) }}" class="badge badge-soft p-4  badge-danger " style="width:100%">
                <div class="box-title"><!-- add .noborder class if box-body is removed -->

                    <i class="h5 fi fi-eye-disabled"></i>
                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['raw_grievance_statistics']['new'] }} Unseen
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['raw_grievance_statistics']['new']) }} नहेरिएको 
                    </h4>
                    @endif

                    <!-- <small class="block">18 Greivance are resolved in this month</small> -->
                </div>
            </a>
        </div>
        <!-- /BOX -->

    </div>
</div>

@else
<div class="row">
    <!-- Orders Box -->
    <div class="col-md-4 col-sm-6 p-1">

        <!-- BOX -->
        <div class="box info"><!-- default, danger, warning, info, success -->
            <a href="{{route('grievance.grievances.index') }}" class="badge p-4  badge-info " style="width:100%">
                <div class="box-title"><!-- add .noborder class if box-body is removed -->
                    <i class="h5 fi fi-pencil"></i>
                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['grievance_statistics']['total'] }} Total Grievance 
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['grievance_statistics']['total']) }} कुल प्राप्त गुनासो 
                    </h4>
                    @endif
                    <!-- <small class="block">18 Greivance are resolved in this month</small> -->
                </div>
            </a>
        </div>
        <!-- /BOX -->

    </div>

    <!-- Profit Box -->
    <div class="col-md-4 col-sm-6 p-1">

        <!-- BOX -->
        <div class="box success"><!-- default, danger, warning, info, success -->
            <a href="#" class="badge p-4  badge-success " style="width:100%">
                <div class="box-title"><!-- add .noborder class if box-body is removed -->
                    <i class="h5 fi fi-check"></i>
                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['grievance_statistics']['closed'] }} Resolved Grievances
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['grievance_statistics']['closed']) }} फर्छ्यौट भएको  
                    </h4>
                    @endif
                    <!-- <small class="block">3 Grievances are in progress in this month</small> -->
                </div>
            </a>
        </div>
        <!-- /BOX -->

    </div>

    <!-- Feedback Box -->
    <div class="col-md-4 col-sm-6 p-1">

        <!-- BOX -->
        <div class="box warning"><!-- default, danger, warning, info, success -->
            <a href="#" class="badge p-4  badge-warning " style="width:100%">
                <div class="box-title"><!-- add .noborder class if box-body is removed -->
                    <i class=" h5 fi fi-circle-spin"></i>
                    @if(App::isLocale('en'))    
                    <h5>
                        {{ $data['grievance_statistics']['inprogress'] }} Inprogress
                    </h5>
                    @else
                    <h4>
                        {{ ViewHelper::ConvertNumberEnToNp($data['grievance_statistics']['inprogress']) }} प्रकृयामा रहेको  
                    </h4>
                    @endif
                    <!-- <small class="block">654 New Grievance this month</small> -->
                    
                </div>
            </a>
        </div>
        <!-- /BOX -->

    </div>

</div>
@endif