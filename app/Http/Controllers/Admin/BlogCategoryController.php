<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BlogCategory\AddFormValidation;
use App\Http\Requests\Admin\BlogCategory\EditFormValidation;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

class BlogCategoryController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.blog-category';
    protected $view_path = 'admin.blog_category';
    protected $panel = 'Blog Category';

    public function __construct()
    {
        $this->model = new BlogCategory();
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
            ->select(
                'id', 'category_name', 'slug', 'status', 'created_at', 'updated_at'
            )
            ->where(function ($query) use ($request) {
                if ($request->has('filter_category_name') && $request->get('filter_category_name')) {

                    $query->Where('category_name', 'like', '%' . $request->get('filter_category_name') . '%');
                }
                if ($request->has('filter_slug') && $request->get('filter_slug')) {

                    $query->Where('slug', 'like', '%' . $request->get('filter_slug') . '%');
                }
                if ($request->has('filter_created_at') && $request->get('filter_created_at')) {
                    $query->where('created_at', 'like', $request->get('filter_created_at') . '%');
                }
                if ($request->has('filter_status') && $request->get('filter_status') && $request->get('filter_status') !== 'all') {
                    $query->where('status', $request->get('filter_status') == 'active' ? 1 : 0);
                }
            })
            ->paginate($data['per_page']);

        $data['request'] = $request->all();

        return view(parent::loadDefaultDataToView($this->view_path . '.index'), compact('data'));
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

        return view(parent::loadDefaultDataToView($this->view_path . '.create'), compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddFormValidation $request)
    {
        abort_unless(\Gate::allows('create-'.Str::lower($this->panel)), 403);
        DB::beginTransaction();
        $section = $this->model->create([
            'user_id' => Auth::user()->id,
            'category_name' => $request->get('category_name'),
            'icon' => $request->get('icon'),
            'slug' => $request->get('slug'),
            'status' => $request->get('status'),
        ]);


        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('create a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $section->id) . '">' . $request->get('category_name') . '</a>', $this->panel, $section->id, 'created');
        DB::commit();

        $request->session()->flash('success_message', $this->panel . ' added successfully.');
        return parent::redirectRequest($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        abort_unless(\Gate::allows('show-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['row'] = $this->model->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route . '.index');
        }

        $data['activitylogs'] = ActivityLog::where('panel', $this->panel)->where('panel_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);

        return view(parent::loadDefaultDataToView($this->view_path . '.show'), compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        abort_unless(\Gate::allows('update-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['row'] = $this->model->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route . '.index');
        }

        return view(parent::loadDefaultDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
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
            return redirect()->route($this->base_route . '.index');
        }

        $data['row']->update([
            'category_name' => $request->get('category_name'),
            'icon' => $request->get('icon'),
            'slug' => $request->get('slug'),
            'status' => $request->get('status'),
        ]);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $request->get('category_name') . '</a>', $this->panel, $id, 'updated');
        DB::commit();

        $request->session()->flash('success_message', $this->panel . ' updated successfully.');
        return parent::redirectRequest($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        abort_unless(\Gate::allows('delete-'.Str::lower($this->panel)), 403);
        $data = [];
        DB::beginTransaction();

        $data['row'] = $this->model->find($id);

        $blogs_count = $data['row']->blogs()->count();
        if ($blogs_count > 0) {
            $request->session()->flash('error_message', 'The Blog Category is used in blogs. Please removed associated blogs before delete');
            return redirect()->route($this->base_route.'.index');
        }
        else
        {
            if (!$this->deleteRow($data['row'])) {
                $request->session()->flash('error_message', 'Invalid request.');
                return redirect()->route($this->base_route.'.index');
            }

            if(config('custom.activity_log') == true)
                ActivityLog::makeActivity('delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->category_name . '</a>', $this->panel, $id, 'deleted');
            DB::commit();


            $request->session()->flash('success_message', 'Blog Category deleted successfully.');
            return redirect()->route($this->base_route.'.index');
        }
    }

    protected function deleteRow($row)
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
}
