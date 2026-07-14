<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\SiteConfig;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mail;
use ViewHelper;
use Carbon\Carbon;
use Lang;

class SiteConfigController extends BaseController
{
    protected $base_route = 'admin.site_config';
    protected $view_path = 'admin.site_config';
    protected $panel = 'Site Configuration';
    protected $model;
    protected $image_name = null;
    protected $folder;
    protected $folder_path;

    public function __construct()
    {

        $this->model = new SiteConfig();
        $this->folder = config('myPath.assets.panel_image_folders.site-config');
        $this->folder_path = public_path('images'.DIRECTORY_SEPARATOR.$this->folder);

    }

    public function edit(Request $request)
    {
        abort_unless(\Gate::allows('show-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['rows'] = $this->model->all()->groupBy('config_keys');
        $data['requests'] = $request->all();
        $data['requests']['form-name'] = $request->get('form-name')?:'general_setting';

        return view(parent::loadDefaultDataToView($this->view_path.'.edit'), compact(['data']));
    }
    
    public function update(Request $request)
    {
        abort_unless(\Gate::allows('update-'.Str::lower($this->panel)), 403);
        DB::beginTransaction();

        foreach ($request->except(['_token','submit']) as $key => $value){
            switch($key){
                case 'image':
                    if($request->get('form-name') == 'general_setting')
                    {
                        $row = $this->model->where('config_keys', 'logo')->first();
                        if($row){
                            $row->config_values = $this->image_name ? $this->image_name : $row->logo;
                            parent::uploadImage($request, 'update', $row->config_values);
                            $row->config_values = $this->image_name ? $this->image_name : $row->logo;
                            $row->save();
                        }
                        else
                        {
                            parent::uploadImage($request, 'insert', 'new_logo');
                            $this->model->create(['config_keys' => 'logo', 'config_values'=> $this->image_name ? $this->image_name : $row->logo]);
                        }
                        break;
                    }
                    elseif($request->get('form-name') == 'seo_setting')
                    {
                        $row = $this->model->where('config_keys', 'banner')->first();
                        parent::uploadImage($request, 'update', $row->config_values);
                        $row->config_values = $this->image_name ? $this->image_name : $row->banner;
                        $row->save();
                        break;
                    }
                    else
                    {
                        break;
                    }
                case 'allowed_file_extension':
                    $row = $this->model->where('config_keys', $key)->first();
                    $extensions = json_encode($value);
                    if($row)
                    {
                        $row->config_values = $extensions;
                        $row->save();
                    }
                    else
                    {
                        $this->model->create(['config_keys' => $key, 'config_values'=> $extensions]);
                    }

                    break;
                default:
                    $row = $this->model->where('config_keys', $key)->first();
                    if($row)
                    {
                        $row->config_values = $value;
                        $row->save();
                    }
                    else
                    {
                        $this->model->create(['config_keys' => $key, 'config_values'=> $value]);
                    }
                    break;
            }
        }
        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a '.$this->panel, $this->panel, 0, 'updated');
        DB::commit();

        $request->session()->flash('success_message', config('custom.setting_tabs.'.$request->get('form-name')).' updated successfully!!');

        return redirect()->route($this->base_route . '.edit',['form-name'=> $request->get('form-name')]);

    }

    public function designEdit()
    {
        abort_unless(\Gate::allows('edit-'.Str::lower($this->panel)), 403);
        $data = [];
        $data['rows'] = $this->model->all()->groupBy('config_keys');
        $news_tab_widget_first = $this->model->where('config_keys','news_tab_widget_first')->first();
        if($news_tab_widget_first)
            $data['row']['news_tab_widget_first']= json_decode($news_tab_widget_first->config_values);
        else
        $data['row']['news_tab_widget_first']= null;

        $news_tab_widget_second = $this->model->where('config_keys','news_tab_widget_second')->first();
        if($news_tab_widget_second)
            $data['row']['news_tab_widget_second']= json_decode($news_tab_widget_second->config_values);
        else
            $data['row']['news_tab_widget_second']= null;

        $news_tab_widget_third = $this->model->where('config_keys','news_tab_widget_third')->first();
        if($news_tab_widget_third)
            $data['row']['news_tab_widget_third']= json_decode($news_tab_widget_third->config_values);
        else
            $data['row']['news_tab_widget_third']= null;
        
        $data['categories'] = NewsCategory::where('status', 1)->pluck('category_name_np', 'id');


        return view(parent::loadDefaultDataToView($this->view_path.'.design_edit'), compact('data'));
    }

    public function designUpdate(Request $request)
    {
        abort_unless(\Gate::allows('edit-'.Str::lower($this->panel)), 403);
        DB::beginTransaction();
        $news_tab_widget_first = $request->news_tab_widget_first;
        $news_tab_widget_second = $request->news_tab_widget_second;
        $news_tab_widget_third = $request->news_tab_widget_third;
        $first_update = SiteConfig::where('config_keys', 'news_tab_widget_first')->first();
        $second_update = SiteConfig::where('config_keys', 'news_tab_widget_second')->first();
        $third_update = SiteConfig::where('config_keys', 'news_tab_widget_third')->first();

        if($first_update)
        {
            $first_update->update([
                'config_values' => json_encode($news_tab_widget_first),
            ]);
        }
        else
        {
            SiteConfig::create([
                'config_keys' => 'news_tab_widget_first',
                'config_values' => json_encode($news_tab_widget_first),
            ]);
        }
        

        if($news_tab_widget_second && $second_update)
        {
            $second_update->update([
                'config_values' => json_encode($news_tab_widget_second),
            ]);
        }
        else
        {
            SiteConfig::create([
                'config_keys' => 'news_tab_widget_second',
                'config_values' => json_encode($news_tab_widget_second),
            ]);
        }

        if($news_tab_widget_third && $third_update)
        {
            $third_update->update([
                'config_values' => json_encode($news_tab_widget_third),
            ]);
        }
        else
        {
            SiteConfig::create([
                'config_keys' => 'news_tab_widget_third',
                'config_values' => json_encode($news_tab_widget_third),
            ]);
        }

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('update a Design Config ', $this->panel, 0, 'updated');
        DB::commit();

        $request->session()->flash('success_message', 'Design Config updated successfully!!');

        return redirect()->back();

    }
    public function sendTestMail(Request $request)
    {
        if (config('custom.mail_notice')) {

            $data['name']          = 'Mail Checker';
            $data['email']         = $request->test_mail_address;
            $data['subject']         = 'This is test email';
            $send['user'] = Mail::send('emails.test_mail', ['data' => $data], function ($m) use ($data) {
                $m->from(config('custom.mail_from'), config('custom.mail_from_name'));
                $m->to($data['email'], $data['name']);
                $m->subject($data['subject']);
            });
            $request->session()->flash('success_message', 'Mail Send Successfully');
        }
        else
        {
            $request->session()->flash('error_message', 'Mail Notice Disabled');
        }


        return redirect()->back();
    }
    public function sendTestSMS(Request $request)
    {
        if (config('custom.sms_notice')) {
            $message = $request->get('test_msg');
            $phone = $request->get('test_phone');;
            $response = ViewHelper::sendSMS($phone, $message);
            $request->session()->flash('success_message', 'SMS Send Successfully');
        }
        else
        {
            $request->session()->flash('error_message', 'SMS Notice Disabled');
        }

        return redirect()->back();
    }

}
