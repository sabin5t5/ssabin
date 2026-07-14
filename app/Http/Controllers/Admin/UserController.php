<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\User\AddFormValidation;
use App\Http\Requests\Admin\User\EditFormValidation;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;


class UserController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.users';
    protected $view_path = 'admin.user';
    protected $panel = 'Users';

    public function __construct()
    {
        $this->model = new User();
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
                'users.id', 'users.name', 'users.email', 'users.created_at', 'users.updated_at', 'users.status'
            )
            ->where('users.id', '!=', '1')
            ->where('users.id', '!=', Auth::user()->id)
            ->where(function ($query) use ($request) {                
                if ($request->has('filter_email') && $request->get('filter_email')) {
                    $query->where('users.email', 'like', '%' . $request->get('filter_email') . '%');
                }
                if ($request->has('filter_created_at') && $request->get('filter_created_at')) {
                    $query->where('users.created_at', 'like', $request->get('filter_created_at') . '%');
                }

                if ($request->has('filter_updated_at') && $request->get('filter_updated_at')) {
                    $query->where('users.updated_at', 'like', $request->get('filter_updated_at') . '%');
                }

            })->orderBy('created_at','desc')->paginate($data['per_page']);
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
        $data = [];
        abort_unless(\Gate::allows('create-'.Str::lower($this->panel)), 403);
        $data['roles'] = Role::whereNot('name', 'super-admin')->select('id', 'name')->get();
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
        $user = $this->model->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'status' => $request->get('status'),
        ]);

        $user->assignRole($request->get('roles'));

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('create a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $user->id) . '">' . $request->get('name') . '</a>', $this->panel, $user->id, 'created');
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
        $data['row'] = $this->model->where('id', '!=', '1')->where('users.id', '!=', Auth::user()->id);
        $data['row'] =$data['row']->find($id); //withTrashed()

        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        $data['activitylogs'] = ActivityLog::where('panel', $this->panel)->where('panel_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        
         $data['row']->roles = $data['row']->roles()->select('roles.id', 'roles.name')->get()->toArray();

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
        $data['row'] = $this->model->where('id', '!=', '1')->where('users.id', '!=', Auth::user()->id);
        $data['row'] =$data['row']->find($id);

        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        $data['row']->roles = $data['row']->roles()->pluck('name', 'name')->toArray();

        $data['roles'] = Role::select('id', 'name')->whereNot('name', 'super-admin')->get();
        return view(parent::loadDefaultDataToView($this->view_path . '.edit'), compact('data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Reques $data ['districts'] =  $this->getDistrict();t $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditFormValidation $request, $id)
    {
        abort_unless(\Gate::allows('update-'.Str::lower($this->panel)), 403);
        $data = [];
        DB::beginTransaction();
        $data['row'] = $this->model->where('id', '!=', '1')->where('users.id', '!=', Auth::user()->id);
        $data['row'] =$data['row']->find($id);

        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route . '.index');
        }

        $data['row']->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password') ? bcrypt($request->get('password')) : $data['row']->password,
            'status' => $request->get('status'),
        ]);

        $data['row']->syncRoles($request->get('roles'));

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
        $data = [];

        abort_unless(\Gate::allows('delete-'.Str::lower($this->panel)), 403);

        $data['row'] = $this->model->where('id', '!=', '1')->where('users.id', '!=', Auth::user()->id);
        $data['row'] =$data['row']->find($id);

        if (!$this->delete($data['row'])) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route . '.index');
        }

        $request->session()->flash('success_message', $this->panel . ' deleted successfully.');
        return redirect()->route($this->base_route . '.index');
    }

    protected function delete($row)
    {
        abort_unless(\Gate::allows('delete-'.Str::lower($this->panel)), 403);

        $row->delete();
        $row->syncRoles([]);
        return true;
    }


}
