<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\StartUpNotice;

use App\Http\Requests\Admin\StartUpNotice\AddFormValidation;
use App\Http\Requests\Admin\StartUpNotice\EditFormValidation;
use Illuminate\Support\Str;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;

class StartUpNoticeController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.start_up_notice';
    protected $view_path = 'admin.start_up_notice';
    protected $image_name = null;
    protected $image_dimensions;
    protected $panel = 'Pop Up Notice';
     protected $folder;
     protected $folder_path;

    public function __construct(StartUpNotice $start_up_notice)
    {
        $this->model = $start_up_notice;
         $this->folder = config('myPath.assets.panel_image_folders.start_up_notice');
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
            ->select( 'id', 'title_en', 'title_np', 'description_en', 'description_np', 'image', 'published_from', 'published_to', 'status'
            );

        if ($request->get('data-show') == 'trashed') {
            $data['rows'] = $data['rows']->onlyTrashed();
            $data['is_trash'] = true;
        }
        else 
        {
            $data['rows'] = $data['rows']->where(function ($query) use ($request){

                if ($request->has('filter_title_en') && $request->get('filter_title_en')) {

                        $query->Where('title_en', 'like', '%'. $request->get('filter_title_en').'%');

                }
                if ($request->has('filter_title_np') && $request->get('filter_title_np')) {

                        $query->Where('title_np', 'like', '%'. $request->get('filter_title_np').'%');
                }

                if ($request->has('filter_published_from') && $request->get('filter_published_from')) {
                    $query->where('published_from', 'like', $request->get('filter_published_from').'%');
                }
                if ($request->has('filter_published_to') && $request->get('filter_published_to')) {
                    $query->where('published_to', 'like', $request->get('filter_published_to').'%');
                }

                if ($request->has('filter_status') && $request->get('filter_status') && $request->get('filter_status') !== 'all') {
                    $query->where('status', $request->get('filter_status') == 'active'?1:0);
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
        // $data['roles'] = Role::select('id', 'name')->get();
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
        if($request->file('image'))
        {
            $allowedfileExtension = config('custom.allowedfileExtension');
            $extension = $request->file('image')->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);

            if ($check)
                parent::uploadImage($request);
            else
            {
                $request->session()->flash('error_message', $extension . '.not allowed.');
                return redirect()->route($this->base_route . '.index');
            }
        }

        $start_up_notice = $this->model->create([
            'title_en' => $request->get('title_en'),
            'title_np' => $request->get('title_np'),
            'description_en' => $request->get('description_en'),
            'description_np' => $request->get('description_np'),
            'image' => $this->image_name,
            'published_from' => $request->get('published_from'),
            'published_to' => $request->get('published_to'),
            'user_id' => Auth::user()->id,
            'status' => $request->get('status'),

        ]);
        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('create a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $start_up_notice->id) . '">' . $request->get('title_en') . '</a>', $this->panel, $start_up_notice->id, 'created');
        DB::commit();
        // ActivityLog::makeActivity('create a new post <a href="' . route($this->base_route . '.show', $user->id) . '">' . $request->get('title') . '</a>', 'post', $user->id, 'created');
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
        $data['row'] = $this->model->select('id', 'user_id as UserId', 'title_en', 'title_np', 'description_en', 'description_np', 'image', 'published_from', 'published_to', 'status', 'created_at', 'updated_at', 'deleted_at'
            )->withTrashed()->find($id);
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
        DB::beginTransaction();
        $data = [];
        $data['row'] = $this->model->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        $this->image_name = $data['row']->image;

        if($request->file('image'))
        {
            $allowedfileExtension = config('custom.allowedfileExtension');
            $extension = $request->file('image')->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);

            if ($check) {
                parent::uploadImage($request, 'update', $data['row']->image);

                parent::removeImage($data['row']->image);
            }
            else
            {
                $request->session()->flash('error_message', $extension . '.not allowed.');
                return redirect()->route($this->base_route . '.index');
            }
        }

        $data['row']->update([

        	'title_en' => $request->get('title_en'),
            'title_np' => $request->get('title_np'),
            'description_en' => $request->get('description_en'),
            'description_np' => $request->get('description_np'),
            'image' => $this->image_name,
            'published_from' => $request->get('published_from'),
            'published_to' => $request->get('published_to'),
            'user_id' => Auth::user()->id,
            'status' => $request->get('status'),
        ]);
        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel .' <a href="' . route($this->base_route . '.show', $data['row']->id) . '">' . $request->get('title_en') . '</a>', $this->panel, $data['row']->id, 'updated');
        DB::commit();
        // ActivityLog::makeActivity('update a news <a href ="' . route($this->base_route . '.show', $id) . '" >' . $request->get('title') . '</a>', 'news', $id, 'updated');
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
        DB::beginTransaction();
        $data = [];
        $data['row'] = $this->model->find($id);
        if (!$this->delete($data['row'])) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title_en . '</a>', $this->panel, $id, 'deleted');
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
            ActivityLog::makeActivity('restore a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title_en . '</a>', $this->panel, $id, 'restored');
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
        // ActivityLog::where('panel', 'post')->where('panel_id', $id)->update(['status'=> 0]);

        // ActivityLog::makeActivity('permanently delete a  post  '.$data['row']->title, 'post', $id, 'forceDeleted');
        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('permanently delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title_en . '</a>', $this->panel, $id, 'restored');
        DB::commit();

        $request->session()->flash('success_message', $this->panel.' delete permanently.');
        return redirect()->route($this->base_route.'.index');
    }

    protected function delete($row)
    {
        abort_unless(\Gate::allows('delete-'.Str::lower($this->panel)), 403);
        if (!$row || !$row->isDeletable()) {
            return false;
        }

        $row->delete();
        parent::removeImage($row->image);
        parent::removeImageThumbs($row->image);
        return true;
    }


    public function upload(Request $request)
    {

        $image = property_exists($this, 'image_request') && $this->image_request ? $this->image_request : $request->file('image');

        if ($image) {

            $this->image_name = time() . mt_rand(4100, 9999) . '_' . $image->getClientOriginalName();
            if (!file_exists($this->folder_path)) {
                File::makeDirectory($this->folder_path . DIRECTORY_SEPARATOR . $org_slug, 0775, true);
            }
            $image->move($this->folder_path . DIRECTORY_SEPARATOR . $org_slug, $this->image_name);

        }
    }
}
