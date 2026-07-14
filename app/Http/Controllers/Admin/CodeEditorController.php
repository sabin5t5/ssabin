<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class CodeEditorController extends Controller
{
    private string $root;

    public function __construct()
    {
        $this->root = base_path('resources'); // CHANGE if needed
    }

    private function safePath($path)
    {
        $full = realpath($this->root . '/' . $path);
        if (!$full || !str_starts_with($full, $this->root)) {
            abort(403);
        }
        return $full;
    }

    public function index(Request $request)
    {
        $path = $request->get('path', '');
        $dir = $this->safePath($path ?: '.');

        return view('admin.editor.index', [
            'files' => File::directories($dir),
            'items' => File::files($dir),
            'path'  => $path
        ]);
    }

    public function open(Request $request)
    {
        $file = $this->safePath($request->file);
        return response()->json([
            'content' => File::get($file)
        ]);
    }

    public function save(Request $request)
    {
        $file = $this->safePath($request->file);
        File::put($file, $request->content);
        return response()->json(['status' => 'saved']);
    }

    public function upload(Request $request)
    {
        $dir = $this->safePath($request->path ?? '');
        $request->file('file')->move($dir, $request->file('file')->getClientOriginalName());
        return back();
    }

    public function mkdir(Request $request)
    {
        $dir = $this->safePath($request->path ?? '');
        File::makeDirectory($dir . '/' . $request->name);
        return back();
    }

    public function delete(Request $request)
    {
        $target = $this->safePath($request->target);
        File::delete($target);
        return back();
    }

    public function rename(Request $request)
    {
        File::move(
            $this->safePath($request->old),
            $this->safePath($request->new)
        );
        return back();
    }
}
