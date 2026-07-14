<?php

namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Admin\Media\AddFormValidation;
use App\Http\Requests\Admin\Media\EditFormValidation;
use App\Models\Admin\Media;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Auth;
use File;

class MediaController extends BaseController
{
    protected $model;
    protected $view_path = 'admin.media';
    protected $base_route = 'admin.media';
    protected $panel = 'Media';
    protected $folder;
    protected $folder_path;
    protected $image_name = null;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Media $slider)
    {
        $this->model = $slider;
        $this->folder = config('myPath.assets.panel_image_folders.media');
        $this->folder_path = public_path('images'.DIRECTORY_SEPARATOR.$this->folder);
    }

    public function index(Request $request)
    {
        abort_unless(\Gate::allows('show-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['per_page'] = $request->per_page ? $request->per_page : 10;

        $data['rows'] = $this->model
            ->select('id','caption_title','image');

        if ($request->get('data-show') == 'trashed') {
            $data['rows'] = $data['rows']->onlyTrashed();
            $data['is_trash'] = true;
        }
        else 
        {
            $data['rows'] = $data['rows']->where(function ($query) use($request){

                                        if($request->has('filter_caption_title') && $request->get('filter_caption_title'))
                                            $query->where('caption_title', 'like', '%'.$request->get('filter_caption_title').'%');

                                        if($request->has('filter_created_at') && $request->get('filter_created_at'))
                                            $query->where('created_at', 'like', '%'.$request->get('filter_created_at').'%');
                                    });
            $data['is_trash'] = false;

        }

        $data['rows'] = $data['rows']->orderby('created_at', 'desc')->paginate($data['per_page']);

        $data['request'] = $request->all();
        return view(parent::loadDefaultDataToView($this->view_path.'.index'), compact('data'));

    }

    public function create()
    {
        abort_unless(\Gate::allows('create-'.Str::lower($this->panel)), 403);
        return view(parent::loadDefaultDataToView($this->view_path.'.create'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        abort_unless(\Gate::allows('create-'.Str::lower($this->panel)), 403);;
        $image = $request->file('file');
        $store = false;
        DB::beginTransaction(); 
        if ($image)
        {
            $this->image_name = time() . mt_rand(4100, 9999) . '_' . $image->getClientOriginalName();

            if (!file_exists($this->folder_path)) {
                File::makeDirectory($this->folder_path, 0775, true);
            }

            $store = $image->move($this->folder_path, $this->image_name);

        }

        if($store)
        {
            $media = Media::create([
                'image' => $this->image_name,
                'caption_title' => $image->getClientOriginalName(),
                'caption_body' => 'null',
                'alt_text' => 'null',
                'status' => 1,
                'user_id' => Auth::user()->id,
            ]);
            if(config('custom.activity_log') == true)
                ActivityLog::makeActivity('create a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $media->id) . '">' . $image->getClientOriginalName() . '</a>', $this->panel, $media->id, 'created');
            DB::commit();

        }

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
        $data['row'] = $this->model->find($id);

        if(!$data['row']){
            $request->session()->flash('error_message', 'Invalid Request!!');
            return redirect()->route($this->base_route.'.index');
        }
        $data['activitylogs'] = ActivityLog::where('panel', $this->panel)->where('panel_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view(parent::loadDefaultDataToView($this->view_path.'.edut'), compact('data'));

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

        if(!$data['row']){
            $request->session()->flash('error_message', 'Invalid Request!!');
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
        $data['row'] = $this->model->find($id);
        DB::beginTransaction(); 
        if(!$data['row']){
            $request->session()->flash('error_message', 'Invalid Request!!');
            return redirect()->route($this->base_route.'.index');
        }

        $media = $data['row']->update([
            'caption_title' => $request->get('caption_title'),
            'caption_body' => $request->get('caption_body'),
            'alt_text' => $request->get('alt_text'),
            'status' => 1,
        ]);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel .' <a href="' . route($this->base_route . '.show', $media->id) . '">' . $request->get('caption_title') . '</a>', $this->panel, $media->id, 'updated');
        DB::commit();
        $request->session()->flash('success_message', $this->panel.' updated successfully!!');
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
            DB::rollback();
            $request->session()->flash('error_message', 'Invalid request.');
            return response($request);
        }
        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $data['row']->id) . '">' . $data['row']->caption_title . '</a>', $this->panel, $data['row']->id, 'deleted');
        DB::commit();
        $request->session()->flash('success_message', $this->panel.' deleted successfully.');
        return response($request);
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
        $data['row'] = $this->model->onlyTrashed()->find($id);
        DB::beginTransaction(); 
        if (!$data['row']->restore()) {
            DB::rollback();
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('restore a '.$this->panel .' <a href="' . route($this->base_route . '.show', $data['row']->id) . '">' . $data['row']->caption_title . '</a>', $this->panel, $data['row']->id, 'restored');
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
        $data['row'] = $this->model->onlyTrashed()->find($id);
        DB::beginTransaction(); 

        if (!$data['row']->forceDelete()) {
            DB::rollback();
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        parent::removeImage($data['row']->image);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('permanently delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $data['row']->id) . '">' . $data['row']->caption_title . '</a>', $this->panel, $data['row']->id, 'forceDeleted');
        DB::commit();

        $request->session()->flash('success_message', $this->panel.' delete permanently.');
        return redirect()->route($this->base_route.'.index');
    }

    protected function delete($row)
    {
        if (!$row || !$row->isDeletable()) {
            return false;
        }

        $row->delete();
        return true;
    }

    public function getMedias(Request $request)
    {
        $data = $request->all();
        $query = $this->model;
        // if (isset($data['type']) && $data['type'] !== 'all') {
        //     $query = $query->where('type', $data['type']);
        // }
        $records = $query->orderBy('created_at','desc')->get();
        $view = view('admin.media.includes.media_lists',[
            'records' => $records
        ])->render();
        return response()->json(['view' =>$view],200);
    }
}
