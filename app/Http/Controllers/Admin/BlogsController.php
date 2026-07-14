<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Blogs;
use App\Models\Admin\BlogCategory;

use App\Http\Requests\Admin\Blog\AddFormValidation;
use App\Http\Requests\Admin\Blog\EditFormValidation;
use App\Models\Admin\Tag;
use Illuminate\Support\Str;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use Carbon\Carbon;

class BlogsController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.blogs';
    protected $view_path = 'admin.blogs';
    protected $image_name = null;
    protected $image_dimensions;
    protected $panel = 'Blogs';
     protected $folder;
     protected $folder_path;

    public function __construct(Blogs $blogs)
    {
         $this->model = $blogs;
         $this->folder = config('myPath.assets.panel_image_folders.blogs');
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
            ->select( 'blogs.id','blogs.user_id', 'blogs.title', 'blogs.image', 'blogs.blog_category', 'blogs.published_at', 'blogs.archive_at', 'blogs.slug', 'blogs.status','blogs.is_highlight',
            'blog_categories.category_name as category'
            )->join('blog_categories','blogs.blog_category','blog_categories.id');

        if ($request->get('data-show') == 'trashed') {
            $data['rows'] = $data['rows']->onlyTrashed();
            $data['is_trash'] = true;
            $data['is_archive'] = false;
        }
        elseif($request->get('data-show') == 'archived') {
            $data['rows'] = $data['rows']->whereDate('archive_at', '<', Carbon::now());
            $data['is_archive'] = true;
            $data['is_trash'] = false;
        }
        else 
        {
            $data['rows'] = $data['rows']->where(function ($query) use ($request){
                $query->whereDate('archive_at', '>', Carbon::now())->orWhereNull('archive_at');

                if ($request->has('filter_title') && $request->get('filter_title')) {
                    $query->Where('title', 'like', '%'. $request->get('filter_title').'%');
                }
                if ($request->has('filter_category') && $request->get('filter_category') && $request->get('filter_category') !== 'all') {
                    $query->where('blog_category', $request->get('filter_category'));
                }
                if (($request->has('filter_published_from') && $request->has('filter_published_to')) && ($request->get('filter_published_from') && $request->get('filter_published_to')) ) 
                {
                    $from_date = Carbon::parse($request->get('filter_published_from'))->startOFDay();
                    $to_date = Carbon::parse($request->get('filter_published_to'))->endOfDay();
                    $query->whereBetween('published_at', [$from_date, $to_date]);
                }  
                if ($request->has('filter_status') && $request->get('filter_status') && $request->get('filter_status') !== 'all') {
                    $query->where('blogs.status', $request->get('filter_status') == 'active'?1:0);
                }
            });
            $data['is_trash'] = false;
            $data['is_archive'] = false;
        }
        $data['rows'] = $data['rows']->orderby('blogs.published_at', 'desc')->paginate($data['per_page']);

        $data['request'] = $request->all();
        $data['blog_categories']= ['' => 'All'] + BlogCategory::where('status', 1)->pluck('category_name', 'id')->toArray();


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
        $data['blog_categories'] = BlogCategory::where('status', 1)->pluck('category_name', 'id');
        $data['tags'] = Tag::pluck('name', 'id');

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

        $blogs = $this->model->create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'image' => $this->image_name,
            'blog_category' => $request->get('blog_category'),
            'published_at' => Carbon::parse($request->get('published_at'))->setTimeFromTimeString(Carbon::now()->toTimeString()),
            'archive_at' => $request->get('archive_at') ? Carbon::parse($request->get('archive_at'))->setTimeFromTimeString(Carbon::now()->toTimeString()): null,
            'user_id' => Auth::user()->id,
            'slug' => $request->get('slug'),
            'status' => $request->get('status'),
            'is_highlight' => $request->get('is_highlight'),

        ]);
        $blogs->tags()->attach($request->get('tags'));

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('create a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $blogs->id) . '">' . $request->get('title') . '</a>', $this->panel, $blogs->id, 'created');
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
        $data['row'] = $this->model->select('blogs.id', 'blogs.user_id as publicationUserId', 'blogs.title', 'blogs.description', 'blogs.image', 'blogs.blog_category', 'blogs.published_at', 'blogs.slug', 'blogs.status', 'blogs.published_at', 'blogs.created_at', 'blogs.updated_at', 'blogs.deleted_at','blogs.is_highlight',
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
        $data['blog_categories'] = BlogCategory::where('status', 1)->pluck('category_name', 'id');
        $data['tags'] = Tag::pluck('name', 'id');

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
        	'title' => $request->get('title'),
            'description' => $request->get('description'),
            'image' => $this->image_name,
            'blog_category' => $request->get('blog_category'),
            'published_at' => $request->get('published_at'),
            'archive_at' => $request->get('archive_at'),
            'slug' => $request->get('slug'),
            'status' => $request->get('status'),
            'is_highlight' => $request->get('is_highlight')
        ]);

        $data['row']->tags()->sync($request->get('tags'));

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $request->get('title') . '</a>', $this->panel, $id, 'updated');
        DB::commit();

        // ActivityLog::makeActivity('update a blogs <a href ="' . route($this->base_route . '.show', $id) . '" >' . $request->get('title') . '</a>', 'blogs', $id, 'updated');
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
            DB::rollback();
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
            DB::rollback();
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('restore a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title . '</a>', $this->panel, $id, 'restored');
        DB::commit();
        // ActivityLog::makeActivity('restore a post <a href ="'.route($this->base_route.'.show', $id).'" >'.$data['row']->title.'</a>', 'post', $id, 'restored');

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
        $data['row']->tags()->detach();
        if (!$data['row']->forceDelete()) {
            DB::rollback();
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        parent::removeImage($data['row']->image);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('permanently delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title . '</a>', $this->panel, $id, 'restored');
        DB::commit();
        // ActivityLog::where('panel', 'post')->where('panel_id', $id)->update(['status'=> 0]);

        // ActivityLog::makeActivity('permanently delete a  post  '.$data['row']->title, 'post', $id, 'forceDeleted');

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
        return true;
    }


    public function generateSlug(Request $request)
    {
        $slug = Str::slug($request->get('title'), '-');
        // validate if the slug is unique in selected category
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
    public function deleteFile(Request $request, $id)
    {
        DB::beginTransaction(); 
        $filename = $request->get('filename');
        $blogs = $this->model->where('id', $id)->first();

        if($filename!= null && $blogs != null)
        {
            if($filename == $blogs['image'] )
            {   
                if (file_exists($this->folder_path . DIRECTORY_SEPARATOR . $filename))   
                {
                    unlink($this->folder_path . DIRECTORY_SEPARATOR . $filename);
                    $blogs->update(['image'=>'']); 
                    if(config('custom.activity_log') == true)
                        ActivityLog::makeActivity('delete a file of the blogs  <a href="'.route($this->base_route.'.show', $id).'">'.$blogs->title.'</a>', $this->panel, $id, 'deleted');
                    DB::commit();
                    $request->session()->flash('success_message', "File Deleted Succesfully!");
                    return true;
                }
            }

        }
        $request->session()->flash('error_message', "Unable to Delete File");
        return true;
    }

}
