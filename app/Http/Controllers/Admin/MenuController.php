<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin\Menu;
use App\Models\Admin\PageContent;
use App\Models\Admin\NewsCategory;
use App\Http\Requests\Admin\Menu\AddFormValidation;
use App\Http\Requests\Admin\Menu\EditFormValidation;
use Auth;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.menu';
    protected $view_path = 'admin.menu';
    protected $panel = 'Menu';

    public function __construct()
    {
        $this->model = new Menu();
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
        $data['rows'] = $this->model
            ->select(
                'id', 'menu_type', 'name_en', 'name_np', 'value', 'rank', 'created_at', 'updated_at', 'parent_id','status'
            )
            ->where(function ($query) use ($request) {
                if ($request->has('filter_name_en') && $request->get('filter_name_en')) {

                    $query->Where('name_en', 'like', '%' . $request->get('filter_name_en') . '%');
                }
                if ($request->has('filter_name_np') && $request->get('filter_name_np')) {

                    $query->Where('name_np', 'like', '%' . $request->get('filter_name_np') . '%');
                }
                if ($request->has('filter_value') && $request->get('filter_value')) {

                    $query->Where('value', 'like', '%' . $request->get('filter_value') . '%');
                }
                if ($request->has('filter_created_at') && $request->get('filter_created_at')) {
                    $query->where('created_at', 'like', $request->get('filter_created_at') . '%');
                }
                if ($request->has('filter_status') && $request->get('filter_status') && $request->get('filter_status') !== 'all') {
                    $query->where('status', $request->get('filter_status') == 'active' ? 1 : 0);
                }
            })
            ->where('status', 1)->orderBy('rank', 'ASC')->get();
            $menus =[];
            foreach($data['rows'] as $key => $menu)
            {
                if($menu->parent_id != null)
                {
                    $menus[$menu->parent_id]['data'][]=$menu;
                }
                else
                    $menus[$menu->id]['main']=$menu;
            }
            $data['menus'] = $menus;


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
          $data['menu_types'] = [''=>'Choose Any']+config('custom.menu_types');

          $current_menus=$this->model->whereNull('parent_id')->where('status', 1)->pluck('name_np', 'id')->toArray();
          $data['parents_menus'] = [''=>'Choose Any'] + $current_menus;

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

        $rank = $this->model->max('rank');
        if($rank == null)
            $rank = 0;
        else
            $rank = $rank + 1;


        if($request->get('menu_type') == 'page_menu')
        {
            $menu_data = PageContent::where('page_name', $request->get('value'))->first();
            if($menu_data)
            {
                $name_en = $menu_data->title_en;
                $name_np = $menu_data->title_np;
            }
            else
            {
                $request->session()->flash('error_message', 'Page Not Found.');
                return back()->withInput();
            }
        }
        elseif($request->get('menu_type') =='news_menu')
        {

            $menu_data = NewsCategory::where('slug', $request->get('value'))->first();
            if($menu_data)
            {
                $name_en = $menu_data->category_name_en;
                $name_np = $menu_data->category_name_np ;
            }
            else
            {
                $request->session()->flash('error_message', 'News Category Not found.');
                return back()->withInput();
            }
        }
        else
        {
            $name_en = $request->get('name_en') ;
            $name_np = $request->get('name_np') ;
        }
    
        $menu = $this->model->create([
            'menu_type' => $request->get('menu_type'),
            'name_en' => $name_en,
            'name_np' => $name_np,
            'value' => $request->get('value'),
            'rank' => $rank,
            'parent_id' => $request->get('parent_id'),
            'status' => $request->get('status'),
        ]);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('create a new '.$this->panel .' <a href="' . route($this->base_route . '.show', $menu->id) . '">' . $request->get('name_en') . '</a>', $this->panel, $menu->id, 'created');
        DB::commit();

        // ActivityLog::makeActivity('create a new post category <a href="' . route($this->base_route . '.show', $user->id) . '">' . $request->get('title') . '</a>', 'office-section', $user->id, 'created');

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

        $data['menu_types'] = [''=>'Choose Any']+config('custom.menu_types');

        $current_menus=$this->model->whereNull('parent_id')->where('status', 1)->pluck('name_np', 'id')->toArray();
        $data['parents_menus'] = [''=>'Choose Any'] + $current_menus;


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
        if($request->get('menu_type') == 'page_menu')
        {
            $menu_data = PageContent::where('page_name', $request->get('value'))->first();
            if($menu_data)
            {
                $name_en = $menu_data->title_en;
                $name_np = $menu_data->title_np;
            }
            else
            {
                $request->session()->flash('error_message', 'Page Not Found.');
                return back()->withInput();
            }
        }
        elseif($request->get('menu_type') =='news_menu')
        {
            $menu_data = NewsCategory::where('slug', $request->get('value'))->first();
            if($menu_data)
            {
                $name_en = $menu_data->category_name_en;
                $name_np = $menu_data->category_name_np ;
            }
            else
            {
                $request->session()->flash('error_message', 'News Category Not found.');
                return back()->withInput();
            }
        }
        else
        {
            $name_en = $request->get('name_en') ;
            $name_np = $request->get('name_np') ;
        }
        $data['row']->update([
            'menu_type' => $request->get('menu_type'),
            'name_en' => $name_en,
            'name_np' => $name_np,
            'value' => $request->get('value'),
            'parent_id' => $request->get('parent_id'),
            'status' => $request->get('status'),
        ]);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $request->get('name_en') . '</a>', $this->panel, $id, 'updated');
        DB::commit();

        // ActivityLog::makeActivity('update a post category <a href ="' . route($this->base_route . '.show', $id) . '" >' . $request->get('title') . '</a>', 'office-section', $id, 'updated');

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
        $id = $request->get('key');
        DB::beginTransaction();
        $data['row'] = $this->model->find($id);
        
        $is_parent_count = $this->model->where('parent_id', $data['row']->id)->count();
        if ($is_parent_count > 0) {
            $request->session()->flash('error_message', 'The Menu '.$data['row']->name_np.' is parent menu. Please removed child menu before delete');
            return response($request);
        }
        else
        {
            if (!$this->deleteRow($data['row'])) {
                $request->session()->flash('error_message', 'Invalid request.');
                return response($request);
            }

            if(config('custom.activity_log') == true)
                ActivityLog::makeActivity('delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->name_en . '</a>', $this->panel, $id, 'deleted');
            DB::commit();


            $request->session()->flash('success_message', 'Menu deleted successfully.');
            return response($request);
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
    public function updateRank(Request $request)
    {
        abort_unless(\Gate::allows('update-'.Str::lower($this->panel)), 403);
        if ($request->has('hidden_id') && $request->get('hidden_id')) {
            DB::beginTransaction();
            $data['menu_ids'] = $request->get('hidden_id');
            foreach ($data['menu_ids'] as $key => $sec_id) {
                $rank_order = $key + 1;
                $menus = $this->model->find($sec_id);
                $menus->update([
                    'rank' => $rank_order
                ]);
            }
            if(config('custom.activity_log') == true)
                ActivityLog::makeActivity('update a rank of '.$this->panel, $this->panel, $sec_id, 'updated');
            DB::commit();

            $request->session()->flash('success_message', 'Rank updated successfully!!');

        } else {
            $request->session()->flash('error_message', 'Invalid Request!!');

        }
        return redirect()->route($this->base_route . '.index');
    }
    public function getItems(Request $request)
    {
        $type = $request->get('type');
        // validate if the slug is unique in selected category
        if ($type=="page_menu") 
        {
            $response = [];
            $items = PageContent::pluck('title_np', 'page_name');

            $response = '<label for="title">Pages: <span class="text-danger">*</span></label><select class="form-control form-control-sm" required="required" name="value"><option value>Choose Any</option>';
            foreach($items as $key=>$item)
            {
                $response.='<option value="'.$key.'">'.$item.'</option>';
            }
            $response .= '</select>';

            return response()->json($response);

        } 
        elseif ($type=='news_menu')
        {
            $response = [];
            $items = NewsCategory::pluck('category_name_np', 'slug');

            $response = '<label for="title">News Category: <span class="text-danger">*</span></label><select class="form-control form-control-sm" required="required" name="value"><option value>Choose Any</option>';
            foreach($items as $key=>$item)
            {
                $response.='<option value="'.$key.'">'.$item.'</option>';
            }
            $response .= '</select>';
            return response()->json($response);
        }
        else {
            return response('Slug: ' . $slug . ' already exist, Response::HTTP_UNAUTHORIZED');
        }

    }
    
}
