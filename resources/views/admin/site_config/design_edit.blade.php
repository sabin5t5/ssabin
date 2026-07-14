@extends('admin.includes.layout')

@section('content')
    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'from' =>'site_config',
    'page'=> "Edit"
    ])
    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')

        @can('update-'.Illuminate\Support\Str::lower($panel))

            {{ html()->modelForm($data['row'], 'POST')->route('admin.design_config.update')->open() }}
            <div class="form-group">
                <strong class="col-md-3 text-center">News Tab Widget First</strong>
                <div class="col-md-9">
                    
                    {{ html()->select('news_tab_widget_first[]', $data['categories'], isset($data['row']['news_tab_widget_first']) ? $data['row']['news_tab_widget_first'] :'null')->class('form-control')->id('news_tab_widget_first')->multiple(true) }}
                </div>
            </div>
            <div class="form-group">
                <strong class="col-md-3 text-center">News Tab Widget Second</strong>
                <div class="col-md-9">
                    
                    {{ html()->select('news_tab_widget_second[]', $data['categories'], isset($data['row']['news_tab_widget_second']) ? $data['row']['news_tab_widget_second'] :'null')->class('form-control')->id('news_tab_widget_second')->multiple(true) }}
                </div>
            </div>
            <div class="form-group">
                <strong class="col-md-3 text-center">News Tab Widget Third</strong>
                <div class="col-md-9">
                    
                    {{ html()->select('news_tab_widget_third[]', $data['categories'], isset($data['row']['news_tab_widget_third']) ? $data['row']['news_tab_widget_third'] :'null')->class('form-control')->id('news_tab_widget_third')->multiple(true) }}
                </div>
            </div>
            <div class="form-group">
                <strong class="col-md-3 text-center"></strong>
                <div class="col-md-9">
                      <button type="submit" class="btn btn-info btn-sm button formSubmitBtn">Submit</button>
                </div>
            </div>
            {{ html()->closeModelForm() }}
        @endcan
    </section>

@endsection
@section('js_scripts')
    <script type="text/javascript" src="{{ ViewHelper::getAssetPath('sortable-master/Sortable.min.js','plugins') }}"></script>
    <script>
        $(document).ready(function() {
            $("#news_tab_widget_first").select2().on('change', function (e) {

                selectCategory('#'+$(this).attr('id'));

                var news_tab_widget_first = $(this).select2('data');
                var news_tab_widget_second = $("#news_tab_widget_second").select2('data');
                var news_tab_widget_third = $("#news_tab_widget_third").select2('data');

                var data = objectMerger(news_tab_widget_first,news_tab_widget_second);
                var data = objectMerger(data,news_tab_widget_third);

            });

            $("#news_tab_widget_second").select2().on('change', function (e) {

                selectCategory('#'+$(this).attr('id'));
                var news_tab_widget_first = $("#news_tab_widget_first").select2('data');
                var news_tab_widget_second = $(this).select2('data');
                var news_tab_widget_third = $("#news_tab_widget_third").select2('data');

                var data = objectMerger(news_tab_widget_first,news_tab_widget_second);
                var data = objectMerger(data,news_tab_widget_third);

            });
            $("#news_tab_widget_third").select2().on('change', function (e) {

                selectCategory('#'+$(this).attr('id'));
                var news_tab_widget_first = $("#news_tab_widget_first").select2('data');
                var news_tab_widget_second = $("#news_tab_widget_second").select2('data');
                var news_tab_widget_third = $(this).select2('data');

                var data = objectMerger(news_tab_widget_first, news_tab_widget_second);
                var data = objectMerger(data,news_tab_widget_third);

                });
        });

            function selectCategory(element_id)
            {
                var vals = $(element_id).select2("val");

                    // selects contains all the OTHER select forms
                    var selects = $('select').not('#'+$(element_id).attr('id'));

                    // loop trough all the selects
                    for (var i = 0; i < selects.length; i++) {
                        //re-enable all options before
                        $(selects[i]).find('option').removeAttr('disabled');
                        // loop trough all the values
                        if(vals != null){
                            for (var j = 0; j < vals.length; j++) {
                                // disabled attribute
                                $(selects[i]).find('option[value='+vals[j]+']').attr('disabled', 'disabled');
                            }
                        }
                    }
            }

            function objectMerger(obj1, obj2)
            {
                var arrayList = []
                var obj_c_processed = [];
                for (var i in obj1) {
                    var obj = {id: obj1[i].id, text: obj1[i].text};

                    for (var j in obj2) {
                        if (obj1[i].id == obj2[j].id) {
                            obj_c_processed[c[j].id] = true;
                        }
                    }
                    arrayList.push(obj);
                }

                for (var j in obj2){
                    if (typeof obj_c_processed[obj2[j].id] == 'undefined') {
                        arrayList.push({id: obj2[j].id, text: obj2[j].text});
                    }
                }
                return arrayList;
            }

									
    </script>
@endsection
