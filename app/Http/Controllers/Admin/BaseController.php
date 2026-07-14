<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Libraries\Traits\FileUpload;
use File;
use Illuminate\Support\Str;


class BaseController extends Controller
{
    use FileUpload;

    protected $bulk_action = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'delete' => 'Delete'
    ];

    public function loadDefaultDataToView($view_path)
    {
        // Using Closure based composers...
        if (!\Gate::allows('delete-'.Str::lower($this->panel))) {

            $this->bulk_action = [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ];
        }


        View::composer($view_path, function ($view) {
            $view->with('base_route', $this->base_route);
            $view->with('view_path', $this->view_path);
            $view->with('panel', $this->panel);
            $view->with('bulk_action', $this->bulk_action);
            $view->with('folder', property_exists($this, 'folder') ? $this->folder : '');
            $view->with('now', property_exists($this, 'now') ? $this->now : '');
            $view->with('image_dimension', property_exists($this, 'image_dimension') ? $this->image_dimension : '');


           /* $view->with('special_offers', SpecialOffer::select('slug')->Active()->get());*/

        });
        return $view_path;
    }

    public function bulkAction(Request $request)
    {

        if ($request->has('bulk_action') && $request->has('row_ids')) {
            ;
            // validate pre difined actions
            if (!array_key_exists($request->get('bulk_action'), $this->bulk_action)) {
                $request->session()->flash('error_message', 'Invalid Request.');
                return redirect()->route($this->base_route . '.index');
            }

            // check if ids are available
            if (!$request->get('row_ids')) {
                $request->session()->flash('error_message', 'Please, check the checkbox to perform actions.');
                return redirect()->route($this->base_route . '.index');
            }

            // perform bulk action
            $ids = explode(',', rtrim($request->get('row_ids'), ','));
            $error_message = '';
            $success_count = 0;
            foreach ($ids as $id) {
                $row = $this->model->find($id);
                if ($row) {
                    switch ($request->get('bulk_action')) {
                        case 'active':
                          //  $this->authorize('update', $this->model);
                            $row->status = 1;
                            $row->save();
                            $success_count++;
                            break;
                        case 'inactive':
                           // $this->authorize('update', $this->model);
                            $row->status = 0;
                            $row->save();
                            $success_count++;
                            break;
                        case 'delete':
                            if (\Gate::allows('delete-'.Str::lower($this->panel))) {
                                if (!$this->delete($row)) {
                                    $error_message = $error_message . 'Invalid request on '.$this->panel.'. <br/>';
                                } else
                                    $success_count++;
                            }
                            else
                            {
                                $error_message = $error_message . 'Unauthorized: You are not allow to delete '.$this->panel.'. <br/>';
                            }
                            break;
                    }


                }

            }

            if ($error_message)
                $request->session()->flash('error_message', $error_message);
            if ($success_count > 0)
                $request->session()->flash('success_message', 'Bulk action (' . $this->bulk_action[$request->get('bulk_action')] . ') performed successfully for ' . $success_count . ' rows.');
            return redirect()->route($this->base_route . '.index');

        }

        $request->session()->flash('error_message', 'Invalid Request.');
        return redirect()->route($this->base_route . '.index');
    }

    public function redirectRequest(Request $request, $id = null)
    {

        if ($request->has('submit')) {



            switch ($request->get('submit')) {
                case 'save':
                    return redirect()->route($this->base_route . '.index');
                    break;

                case 'save-continue':
                    return redirect()->route($this->base_route . '.create', $id);
                    break;

                case 'update':
                    return redirect()->route($this->base_route . '.index');
                    break;

                case 'update-continue':
                    return redirect()->route($this->base_route . '.edit', $id);
                    break;

                case 'update-rank-continue';
                    return redirect()->route($this->base_route . '.manage-rank');
                    break;

                default:
                    return redirect()->route($this->base_route . '.index');
            }

        } else
            return redirect()->route($this->base_route . '.index');
    }
    public function checkRowExists($row)
    {
        $check = $this->model->find($row);
        if (!$check)
            return redirect()->route($this->base_route)->send();

        return $check;
    }

}
