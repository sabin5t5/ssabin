<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Tag;
use App\Models\Admin\News;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

use App\Http\Requests\Admin\Tag\AddFormValidation;
use App\Http\Requests\Admin\Tag\EditFormValidation;
use Auth;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class TagController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.tag';
    protected $view_path = 'admin.tag';
    protected $panel = 'Tag';


    public function __construct()
    {
        $this->model = new Tag();
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
                'id', 'name','slug', 'created_at', 'updated_at'
            )
            ->where(function ($query) use ($request) {
                if ($request->has('filter_name') && $request->get('filter_name')) {

                    $query->Where('name', 'like', '%' . $request->get('filter_name') . '%');
                }
                if ($request->has('filter_slug') && $request->get('filter_slug')) {

                    $query->Where('slug', 'like', '%' . $request->get('filter_slug') . '%');
                }
                if ($request->has('filter_created_at') && $request->get('filter_created_at')) {
                    $query->where('created_at', 'like', $request->get('filter_created_at') . '%');
                }
            })->orderBy('created_at', 'asc')
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
        $tag = $this->model->create([
            'user_id' => Auth::user()->id,
            'name' => $request->get('name'),
            'slug' => Str::slug($request->get('slug'), '-'),
        ]);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('Add a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $tag->id) . '">' . $request->get('name') . '</a>', $this->panel, $tag->id, 'created');
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
        $data = [];
        DB::beginTransaction();
        $data['row'] = $this->model->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route . '.index');
        }

        $data['row']->update([
            'name' => $request->get('name'),
            'slug' => Str::slug($request->get('slug'), '-'),
        ]);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $request->get('name') . '</a>', $this->panel, $id, 'updated');
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
        $post_count = Tag::where('id', $id)->first()->news()->count();
        if ($post_count > 0) {
            $request->session()->flash('error_message', 'The Tag '.$data['row']->name.' is used in blog. Please removed blog before delete');
            return back();
        }
        else
        {
            if (!$this->deleteRow($data['row'])) {
                $request->session()->flash('error_message', 'Invalid request.');
                return back();
            }
            if(config('custom.activity_log') == true)
                ActivityLog::makeActivity('delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->name . '</a>', $this->panel, $id, 'deleted');
            DB::commit();

            $request->session()->flash('success_message', $this->panel . ' deleted successfully.');
            return back();
        }
    }


    protected function deleteRow($row)
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
        if ($this->model
                ->where('slug', $slug)
                ->where(function ($query) use ($request) {
                    if ($request->get('id') && $request->get('id') !== 'NULL') {
                        $query->where('id', '!=', $request->get('id'));
                    }
                })
                ->count() == 0) {
            $response = [];
            $response['slug'] = Str::slug($request->get('title'), '-');
            return response()->json($response);

        } else {
            return response('Slug: ' . $slug . ' already exist, Response::HTTP_UNAUTHORIZED');
        }

    }
}
