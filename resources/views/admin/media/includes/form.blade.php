<fieldset>
    <legend>Media</legend>
    <div class="row">
        <div class="col-md-6">
            {{ html()->modelForm($data['row'], 'PUT')->route($base_route.'.update', $data['row']->id)->acceptsFiles()->open() }}

                {{ html()->hidden('id', $data['row']->id) }}
                <div class="form-group">
                    <label for="Caption">Caption Title:<span class="text-danger">*</span></label>
                    {{ html()->text('caption_title')->class('form-control form-control-sm caption_title')->required(true) }}
                </div>
                <div class="form-group">
                    <label for="caption_body">Caption Description:</label>
                    {{ html()->text('caption_body')->class('form-control form-control-sm caption_body') }}
                </div>
                <div class="form-group">
                    <label for="alt">Alt:</label>
                    {{ html()->text('alt_text')->class('form-control form-control-sm alt_text') }}
                </div>
                <div>
                    <button type="submit" name="submit"  class="btn btn-success"> {{ $button }} </button>

                    <a type="button" href="{{ route($base_route.'.index') }}"
                   class="btn btn-danger row-edit">
                     {{ App::isLocale('en') ? 'Cancel' : 'रद्द गर्नुहोस्' }}
                </a>
                </div>
            {{ html()->closeModelForm() }}


             
        </div>
        <div class="col-md-6">
            <div class="form-group">
                 @if ($data['row']->image)
                 <div class="btn-gorup mb-3">
                    @if($data['row']->deleted_at != null)
                        <button class="link_btn btn-sm btn-warning  confirm-restore"
                                    value = "{{ $data['row']->id }}" data-toggle="tooltip" title="Restore">
                            <i class="fi fi-reload"></i>
                        </button>
                        <button class="btn-sm btn-danger  link_btn confirm-forcedelete"
                                    value = "{{ $data['row']->id }}" 
                                    data-toggle="tooltip" title="Delete Permanently">
                                <i class="fi fi-times"></i> 
                            </button>
                    @else
                    <button class="link_btn btn-sm btn-danger confirm-delete-button" value = "{{ $data['row']->id }}">
                        <i class="fi fi-thrash"></i>
                    </button>
                    @endif
                </div>
                <img src="{{ ViewHelper::getImagePath($folder, $data['row']->image) }}" width="500">
                 <div class="input-group mt-3" style="max-width: 500px">
                    <input type="text" value="{{ ViewHelper::getImagePath($folder, $data['row']->image) }}" id="myInput" class="form-control disabled form-control-sm">
                    <div class="input-group-append tooltipclipboard">
                        <button onclick="myFunction()" onmouseout="outFunc()" class="btn btn-sm btn-success">
                            <span class="tooltipclipboardtext" id="myTooltip">Copy to clipboard</span>
                            Copy Link
                        </button>
                    </div>
                </div>

                
                @else
                    <img src="{{ ViewHelper::getImagePath($folder, 'no_image.gif') }}" width="200">
                @endif
            </div>
        </div>
    </div>
</fieldset>

