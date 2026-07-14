@extends('admin.includes.layout')
@section('content')
<div class="container mt-4">

    <h2 class="mb-4">Super Admin Code Editor</h2>

    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.code-editor.index') }}">Root</a></li>
            @php
                $parts = $path ? explode('/', $path) : [];
                $breadcrumbPath = '';
            @endphp
            @foreach($parts as $part)
                @php $breadcrumbPath .= ($breadcrumbPath ? '/' : '') . $part; @endphp
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.code-editor.index', ['path' => $breadcrumbPath]) }}">{{ $part }}</a>
                </li>
            @endforeach
        </ol>
    </nav>

    {{-- Upload & Create Folder --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Upload File</h5>
                <form action="{{ route('admin.code-editor.upload') }}" method="POST" enctype="multipart/form-data" class="form-inline">
                    @csrf
                    <input type="hidden" name="path" value="{{ $path }}">
                    <input type="file" name="file" class="form-control mr-2" required>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Create Folder</h5>
                <form action="{{ route('admin.code-editor.mkdir') }}" method="POST" class="form-inline">
                    @csrf
                    <input type="hidden" name="path" value="{{ $path }}">
                    <input type="text" name="name" placeholder="Folder name" class="form-control mr-2" required>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Folder List --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Folders</strong>
        </div>
        <ul class="list-group list-group-flush">
            @foreach($files as $dir)
                @php $name = basename($dir); @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        📁 <a href="{{ route('admin.code-editor.index', ['path' => $path.'/'.$name]) }}">{{ $name }}</a>
                    </span>
                    <span>
                        <form method="POST" action="{{ route('admin.code-editor.rename') }}" class="form-inline d-inline mr-1">
                            @csrf
                            <input type="hidden" name="old" value="{{ $path.'/'.$name }}">
                            <input type="text" name="new" placeholder="Rename" class="form-control form-control-sm mr-1">
                            <button type="submit" class="btn btn-sm btn-info">Rename</button>
                        </form>
                        <form method="POST" action="{{ route('admin.code-editor.delete') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="target" value="{{ $path.'/'.$name }}">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete folder {{ $name }}?')">Delete</button>
                        </form>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- File List --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Files</strong>
        </div>
        <ul class="list-group list-group-flush">
            @foreach($items as $file)
                @php $name = basename($file); @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        📄 <a href="#" class="file-open" data-file="{{ $path.'/'.$name }}">{{ $name }}</a>
                    </span>
                    <span>
                        <form method="POST" action="{{ route('admin.code-editor.rename') }}" class="form-inline d-inline mr-1">
                            @csrf
                            <input type="hidden" name="old" value="{{ $path.'/'.$name }}">
                            <input type="text" name="new" placeholder="Rename" class="form-control form-control-sm mr-1">
                            <button type="submit" class="btn btn-sm btn-info">Rename</button>
                        </form>
                        <form method="POST" action="{{ route('admin.code-editor.delete') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="target" value="{{ $path.'/'.$name }}">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete file {{ $name }}?')">Delete</button>
                        </form>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Editor Modal --}}
    <div class="modal fade" id="editorModal" tabindex="-1" role="dialog" aria-labelledby="editorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editing: <span id="fileName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeEditor">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="height:70vh;">
                    <div id="editor" style="height:100%; border:1px solid #ccc;"></div>
                </div>
                <div class="modal-footer">
                    <button id="saveFile" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeEditor2">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js_scripts')
<script src="https://unpkg.com/monaco-editor@latest/min/vs/loader.js"></script>
<script>
require.config({ paths: { 'vs': 'https://unpkg.com/monaco-editor@latest/min/vs' }});
require(['vs/editor/editor.main'], function () {
    let editor;
    let currentFile;
    const modal = $('#editorModal');

    $('.file-open').on('click', function(e){
        e.preventDefault();
        currentFile = $(this).data('file');
        $('#fileName').text(currentFile);

        fetch("{{ route('admin.code-editor.open') }}?file=" + encodeURIComponent(currentFile))
        .then(res => res.json())
        .then(data => {
            if(editor) editor.dispose();
            editor = monaco.editor.create(document.getElementById('editor'), {
                value: data.content,
                language: currentFile.endsWith('.js') ? 'javascript' :
                          currentFile.endsWith('.css') ? 'css' :
                          currentFile.endsWith('.blade.php') ? 'html' :
                          'php',
                theme: 'vs-dark',
                automaticLayout: true
            });
            modal.modal('show');
        });
    });

    $('#saveFile').on('click', function(){
        fetch("{{ route('admin.code-editor.save') }}", {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({
                file: currentFile,
                content: editor.getValue()
            })
        }).then(res => res.json())
          .then(data => alert('✅ File saved!'));
    });

    $('#closeEditor, #closeEditor2').on('click', function(){
        modal.modal('hide');
        if(editor) editor.dispose();
    });
});
</script>
@endsection
