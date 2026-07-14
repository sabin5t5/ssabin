<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Role\EditFormValidation;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.roles';
    protected $view_path = 'admin.role';
    protected $panel = 'Role';
    protected $bulk_action = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];


    public function __construct()
    {
        $this->model = new Role();
    }

    public function index(Request $request)
    {
        abort_unless(\Gate::allows('show-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['per_page'] = $request->per_page ? $request->per_page : 10;
        $data['rows'] = $this->model
            ->select(
                'id', 'created_at','name')
            ->where('name', '!=', 'super-admin')
            ->where(function ($query) use ($request){

                if ($request->has('filter_name') && $request->get('filter_name')) {
                    $query->where('name', 'like', '%'.$request->get('filter_name').'%');
                }

                if ($request->has('filter_created_at') && $request->get('filter_created_at')) {
                    $query->where('created_at', 'like', $request->get('filter_created_at').'%');
                }

            })
            ->orderby('created_at', 'asc')->paginate($data['per_page']);
        return view(parent::loadDefaultDataToView($this->view_path.'.index'), compact('data'));
    }

    public function create(Request $request)
    {
        abort_unless(\Gate::allows('create-'.Str::lower($this->panel)), 403);
        $data = [];


        $panel =[];
        $data['all_permissions'] = Permission::get();
        foreach ($data['all_permissions'] as $permissions)
        {
            $name_array = Str::of($permissions->name)->explode('-');

            $panel[$name_array[1]]['panel_name'] = $name_array[1];
            $panel[$name_array[1]]['permissions'][] = ['action_name' => $name_array[0],
                                                 'permission_name' => $permissions->name
                                                ];
        }

        $data['permission_lists'] = $panel ;


        
        return view(parent::loadDefaultDataToView($this->view_path.'.create'), compact('data'));
    }

    public function store(Request $request)
    {
        abort_unless(\Gate::allows('create-'.Str::lower($this->panel)), 403);
        $data = [];
        DB::beginTransaction();
        if($this->model->where('name', $request->get('name'))->first() || $request->get('name') =='super-admin')
        {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }

        $role = $this->model->create(['name'=>$request->get('name')]);

        $role->givePermissionTo($request->get('permission'));


        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('create a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $role->id) . '">' . $request->get('name') . '</a>', $this->panel, $role->id, 'created');
        DB::commit();

        $request->session()->flash('success_message', $this->panel . ' added successfully.');
        return parent::redirectRequest($request);
    }

    public function show(Request $request, $id)
    {
        abort_unless(\Gate::allows('show-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['row'] = $this->model->where('name', '!=', 'super-admin')->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        $panel =[];
        $data['active_permissions'] = $data['row']->permissions()->get();
        foreach ($data['active_permissions'] as $permissions)
        {
            $name_array = Str::of($permissions->name)->explode('-');

            $panel[$name_array[1]]['panel_name'] = $name_array[1];
            $panel[$name_array[1]]['permissions'][] = ['action_name' => $name_array[0],
                                                 'permission_name' => $permissions->name
                                                ];
        }
        $data['permission_lists'] = $panel ; 


        $data['activitylogs'] = ActivityLog::where('panel', $this->panel)->where('panel_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view(parent::loadDefaultDataToView($this->view_path.'.show'), compact('data'));
    }


    public function edit(Request $request, $id)
    {
        abort_unless(\Gate::allows('update-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['row'] = $this->model->where('name', '!=', 'super-admin')->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        $data['role_detail'] = $data['row'];
        $data['row']->name = $data['role_detail']->name;
        $data['row']->permission = $data['row']->permissions()->pluck('name', 'name')->toArray();


        $panel =[];
        $data['all_permissions'] = Permission::get();
        foreach ($data['all_permissions'] as $permissions)
        {
            $name_array = Str::of($permissions->name)->explode('-');

            $panel[$name_array[1]]['panel_name'] = $name_array[1];
            $panel[$name_array[1]]['permissions'][] = ['action_name' => $name_array[0],
                                                 'permission_name' => $permissions->name
                                                ];
        }
        $data['permission_lists'] = $panel ;

        

        return view(parent::loadDefaultDataToView($this->view_path.'.edit'), compact('data'));
    }

    public function update(Request $request, $id)
    {
        abort_unless(\Gate::allows('update-'.Str::lower($this->panel)), 403);
        DB::beginTransaction();
        $data = [];
        $data['row'] = $this->model->where('name', '!=', 'super-admin')->find($id);
        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        $data['row']->update($request->except(['_token', '_method', 'id']));


        $data['row']->syncPermissions($request->get('permission'));


        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $request->get('name') . '</a>', $this->panel, $id, 'updated');
        DB::commit();
        $request->session()->flash('success_message', $this->panel.' updated successfully.');
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
        $data['row'] = $this->model->where('name', '!=', 'super-admin')->findById($id);
        $permission_count = $data['row']->permissions()->count();
        if ($permission_count > 0) {
            $request->session()->flash('error_message', 'The Role '.$data['row']->name.' contains some Permission so you cannot delete it. Please removed staff before delete');
                return parent::redirectRequest($request, $id);
        }
        else
        {
            if (!$this->deleteRow($data['row'])) {
                $request->session()->flash('error_message', 'Invalid request.');
                return parent::redirectRequest($request, $id);
            }
            if(config('custom.activity_log') == true)
                ActivityLog::makeActivity('delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->name . '</a>', $this->panel, $id, 'deleted');
            DB::commit();

            $request->session()->flash('success_message', $this->panel . ' deleted successfully.');
            return parent::redirectRequest($request, $id);
        }
    }
    protected function deleteRow($row)
    {
        abort_unless(\Gate::allows('delete-'.Str::lower($this->panel)), 403);
        $row->delete();
        return true;
    }

}
