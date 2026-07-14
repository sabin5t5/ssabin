<div class="gallery">
    @foreach($records as $record)
        <div class="gallery-item" data-id={{ $record->id }}>
            <?php $pathinfo = pathinfo($record->image); ?>
            @if($pathinfo['extension']=='jpg' || $pathinfo['extension']=='JPG' || $pathinfo['extension']=='JPEG' || $pathinfo['extension']=='jpeg' || $pathinfo['extension']=='png'|| $pathinfo['extension']=='PNG' || $pathinfo['extension']=='gif' || $pathinfo['extension']=='GIF' || $pathinfo['extension']=='svg' || $pathinfo['extension']=='SVG')
                <img class="gallery-image" src="{{ asset('images/media/'.$record->image) }}" alt="{{ $record->caption_title }}" data-toggle="tooltip" data-placement="top" title="{{ $record->caption_title }}" />
            @elseif($pathinfo['extension'] === 'doc' || $pathinfo['extension']=='docx')
                <img class="gallery-image" src="{{ asset('images/logo/file.png')}}" alt="{{ $record->caption_title }}" data-toggle="tooltip" data-placement="top" title="{{ $record->caption_title }}" />
            @elseif($pathinfo['extension'] === 'pdf' || $pathinfo['extension']="PDF")
                <img class="gallery-image" src="{{ asset('images/logo/file.png')}}" alt="{{ $record->caption_title }}" data-toggle="tooltip" data-placement="top" title="{{ $record->caption_title }}" />
            @endif
        </div>
    @endforeach
</div>