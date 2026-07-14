<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Http\Requests\Admin\Pages\AddFormValidation;
use App\Http\Requests\Admin\Pages\EditFormValidation;

use App\Models\Admin\Pages;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use File;

class PagesController extends BaseController
{

    protected $model;
    protected $base_route = 'admin.pages';
    protected $view_path = 'admin.pages';
    protected $image_name = null;
    protected $image_dimensions;
    protected $panel = 'Pages';
     protected $folder;
     protected $folder_path;

    public function __construct(Pages $pages)
    {
         $this->model = $pages;
         $this->folder = config('myPath.assets.panel_image_folders.pages');
         $this->folder_path = public_path('images'.DIRECTORY_SEPARATOR.$this->folder);
         $this->image_dimensions = config('myPath.image-dimensions.'.$this->folder);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        abort_unless(\Gate::allows('show-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['per_page'] = $request->per_page ? $request->per_page : 10;

        $data['rows'] = $this->model
            ->select('pages.id', 'pages.title','pages.status','pages.page_name'
            );

        if ($request->get('data-show') == 'trashed') {
            $data['rows'] = $data['rows']->onlyTrashed();
            $data['is_trash'] = true;
        }
        else 
        {
            $data['rows'] = $data['rows']->where(function ($query) use ($request){

                if ($request->has('filter_title') && $request->get('filter_title'))
                {
                 	$query->Where('pages.title', 'like', '%'. $request->get('filter_title').'%');
                }

                if ($request->has('filter_page_name') && $request->get('filter_page_name')) {

                        $query->Where('pages.page_name', 'like', '%'. $request->get('filter_page_name').'%');

                }

                if ($request->has('filter_status') && $request->get('filter_status') && $request->get('filter_status') !== 'all') {

                    $query->where('pages.status', $request->get('filter_status') == 'active'?1:0);
                }

            });
            $data['is_trash'] = false;

        }

        $data['rows'] = $data['rows']->orderby('created_at', 'desc')->paginate($data['per_page']);

        $data['request'] = $request->all();
        return view(parent::loadDefaultDataToView($this->view_path.'.index'), compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_unless(\Gate::allows('create-'.Str::lower($this->panel)), 403);
        $data = [];
        return view(parent::loadDefaultDataToView($this->view_path.'.create'), compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddFormValidation $request)
    {
        abort_unless(\Gate::allows('create-'.Str::lower($this->panel)), 403);
        DB::beginTransaction(); 

        $image_name = NULL;
        $image_okay = false;

        if($request->file('image'))
        {
            $allowedfileExtension = config('custom.allowedfileExtension');

            $extension = $request->file('image')->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);

            if ($check)
                $image_okay = true;
            else
            {
                $request->session()->flash('error_message', $extension . '.not allowed.');
                return redirect()->route($this->base_route . '.index');
            }
        }

        if($image_okay)
            $image_name = $this->upload_image($request, 'image');
        
        $page = $this->model->create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'page_name' => $request->get('page_name'),
            'user_id' => Auth::user()->id,
            'image' => $image_name,
            'meta_title' => $request->get('meta_title'),
            'meta_description' => $request->get('meta_description'),
            'meta_keywords' => $request->get('meta_keywords'),
            'status' => $request->get('status'),

        ]);
        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('create a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $page->id) . '">' . $request->get('title') . '</a>', $this->panel, $page->id, 'created');
        DB::commit();
        $request->session()->flash('success_message', $this->panel . ' added successfully.');
        return parent::redirectRequest($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        abort_unless(\Gate::allows('show-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['row'] = $this->model->select('pages.id', 'pages.title', 'pages.description', 'pages.image','pages.status', 'pages.meta_title', 'pages.meta_description', 'pages.meta_keywords',
                'pages.user_id as UserId', 'pages.created_at', 'pages.updated_at','users.name as username'
            )->join('users','pages.user_id','users.id')->
        withTrashed()->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }

        $data['activitylogs'] = ActivityLog::where('panel', $this->panel)->where('panel_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);


        return view(parent::loadDefaultDataToView($this->view_path.'.show'), compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        abort_unless(\Gate::allows('update-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['row'] = $this->model->find($id);

        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }

        return view(parent::loadDefaultDataToView($this->view_path.'.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditFormValidation $request, $id)
    {
        abort_unless(\Gate::allows('update-'.Str::lower($this->panel)), 403);
        $data = [];
        DB::beginTransaction();
        $data['row'] = $this->model->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        $image_name = $data['row']->image;
        $image_okay = false;

        if($request->file('image'))
        {
            $allowedfileExtension = config('custom.allowedfileExtension');

            $extension = $request->file('image')->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);

            if ($check)
                $image_okay = true;
            else
            {
                $request->session()->flash('error_message', $extension . '.not allowed.');
                return redirect()->route($this->base_route . '.index');
            }
        }

        if($image_okay)
        {
            $image_name = $this->upload_image($request, 'image');
            parent::removeImage($data['row']->image);
        }
        
        $data['row']->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'page_name' => $request->get('page_name'),
            'meta_title' => $request->get('meta_title'),
            'meta_description' => $request->get('meta_description'),
            'meta_keywords' => $request->get('meta_keywords'),
            'status' => $request->get('status'),
            'image' => $image_name,

        ]);
        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel .' <a href="' . route($this->base_route . '.show', $data['row']->id) . '">' . $request->get('title') . '</a>', $this->panel, $data['row']->id, 'updated');
        DB::commit();

        $request->session()->flash('success_message', $this->panel . ' updated successfully.');

        return parent::redirectRequest($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request, $id)
    {
        abort_unless(\Gate::allows('delete-'.Str::lower($this->panel)), 403);
        $data = [];
        DB::beginTransaction();
        $data['row'] = $this->model->find($id);
        if (!$this->delete($data['row'])) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title . '</a>', $this->panel, $id, 'deleted');
        DB::commit();

        $request->session()->flash('success_message', $this->panel.' deleted successfully.');
        return redirect()->route($this->base_route.'.index');
    }

    /**
     * restore the specified resource from trash.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        abort_unless(\Gate::allows('restore-'.Str::lower($this->panel)), 403);
        $data = [];
        DB::beginTransaction();
        $data['row'] = $this->model->onlyTrashed()->find($id);

        if (!$data['row']->restore()) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('restore a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title . '</a>', $this->panel, $id, 'restored');
        DB::commit();

        $request->session()->flash('success_message', $this->panel.' restore successfully.');
        return redirect()->route($this->base_route.'.index');
    }

    /**
     * restore the specified resource from trash.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        abort_unless(\Gate::allows('forceDelete-'.Str::lower($this->panel)), 403);
        $data = [];
        DB::beginTransaction();
        
        $data['row'] = $this->model->onlyTrashed()->find($id);

        if (!$data['row']->forceDelete()) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        parent::removeImage($data['row']->image);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('permanently delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title . '</a>', $this->panel, $id, 'restored');
        DB::commit();

        $request->session()->flash('success_message', $this->panel.' delete permanently.');
        return redirect()->route($this->base_route.'.index');
    }

    protected function delete($row)
    {
        abort_unless(\Gate::allows('delete-'.Str::lower($this->panel)), 403);
        
        if (!$row) {
            return false;
        }

        $row->delete();
        return true;
    }
    public function generateSlug(Request $request)
    {
        $slug = Str::slug($request->get('title'));
        if ($this->model
                ->where('slug', $slug)
                ->where(function ($query) use ($request) {
                    if ($request->get('id') && $request->get('id') !== 'NULL') {
                        $query->where('id', '!=', $request->get('id'));
                    }
                })
                ->count() == 0) {
            $response = [];
            $response['slug'] = Str::slug($request->get('title'));
            return response()->json($response);

        } else {
            return response('Slug: ' . $slug . ' already exist, Response::HTTP_UNAUTHORIZED');
        }

    }


    public function upload_image($request, $image_var)
    {
        $image =$request->file($image_var);

        if ($image) {
            $image_name = time() . mt_rand(4100, 9999) . '_' . $image->getClientOriginalName();

            if (!file_exists($this->folder_path)) {
                File::makeDirectory($this->folder_path, 0775, true);
            }
            $image->move($this->folder_path, $image_name);

            return $image_name ;

        }

    }
    public function deleteFile(Request $request, $record_id, $record_type, $filename)
    {
        DB::beginTransaction(); 
        $id =$record_id;

        $page = $this->model->where('id', $id)->first();

        if($filename!= null && $page != null)
        {
            if($filename == $page[$record_type] )
            {   
                if (file_exists($this->folder_path . DIRECTORY_SEPARATOR . $filename))   
                {
                    unlink($this->folder_path . DIRECTORY_SEPARATOR . $filename);
                    $page->update([$record_type=>'']); 
                    if(config('custom.activity_log') == true)
                        ActivityLog::makeActivity('delete a file of the content page  <a href="'.route($this->base_route.'.show', $id).'">'.$filename.'</a>', $this->panel, $id, 'deleted');
                    DB::commit();
                    $request->session()->flash('success_message', "File Deleted Succesfully!");
                    return back();
                }

            }

        }
        $request->session()->flash('error_message', "Unable to Delete File");
        return redirect()->back()->withInput();
    }

}
