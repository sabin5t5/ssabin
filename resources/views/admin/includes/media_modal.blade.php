<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaModalLabel">File Manager</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#media_list" id="media_list_tab" role="tab" aria-selected="true">
                            Media Lists
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#media-upload" role="tab"  aria-selected="false">
                            Upload  
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div id="media_list"  class=" tab-pane fade show active" role="tabpanel" >
                        <div class="card">
                            <div class="card-body">
                                <div class="gallery-container">
                                     
                                </div>
                                <hr>
                                <button class="btn btn-primary btn-sm pull-right" id="media-apply">Apply</button>
                            </div>
                        </div>
                    </div>
                    <div id="media-upload"  class="tab-pane fade" role="tabpanel" >
                        <div class="card">
                            <div class="card-heading card-heading-transparent">
                                <strong>DROP FILE UPLOAD</strong>
                            </div>
            
                            <div class="card-body">
                            {{ html()->form('POST')->route('admin.media.store')->id('my-dropzone')->class('dropzone nomargin dz-clickable')->acceptsFiles()->open() }}
            
                           
            
                                <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                            {{ html()->form()->close() }}
            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
