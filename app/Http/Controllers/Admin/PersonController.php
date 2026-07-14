<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin\Person;

use App\Http\Requests\Person\Blog\EditFormValidation;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use Carbon\Carbon;

class PersonController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.person';
    protected $view_path = 'admin.person';
    protected $image_name = null;
    protected $image_dimensions;
    protected $panel = 'Person';
     protected $folder;
     protected $folder_path;

    public function __construct(Person $person)
    {
         $this->model = $person;
         $this->folder = config('myPath.assets.panel_image_folders.person');
         $this->folder_path = public_path('images'.DIRECTORY_SEPARATOR.$this->folder);
         $this->image_dimensions = config('myPath.image-dimensions.'.$this->folder);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('show-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['per_page'] = $request->per_page ? $request->per_page : 10;
        $data['rows'] = $this->model
            ->select( 'people.id','people.user_id', 'people.title', 'people.first_name','people.last_name', 'people.gender', 'people.current_address', 'people.image'
            );

        
        $data['rows'] = $data['rows']->where(function ($query) use ($request){

            if ($request->has('filter_name') && $request->get('filter_name')) {
                $query->Where('first_name', 'like', '%'. $request->get('filter_name').'%')
                ->orWhere('middle_name', 'like', '%'. $request->get('filter_name').'%')
                ->orWhere('last_name', 'like', '%'. $request->get('filter_name').'%');
            }
        });

        $data['rows'] = $data['rows']->orderby('people.created_at', 'desc')->paginate($data['per_page']);

        $data['request'] = $request->all();
        return view(parent::loadDefaultDataToView($this->view_path.'.index'), compact('data'));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

}