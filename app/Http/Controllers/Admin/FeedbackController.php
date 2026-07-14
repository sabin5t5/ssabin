<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Bills;
use App\Models\Admin\Feedback;
use App\Models\Admin\FeedbackResult;

use Illuminate\Support\Str;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use App\Http\Requests\FeedbackReplyValidation;
use Mail;

class FeedbackController extends BaseController
{
    protected $model;
    protected $base_route = 'admin.feedback';
    protected $view_path = 'admin.feedback';
    protected $panel = 'Feedback';

    public function __construct(Feedback $feedback)
    {
         $this->model = $feedback;

        $this->bulk_action = [
            'new' => 'New',
            'replied' => 'Replied',
            'delete' => 'Delete'
        ];
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
            ->select('id', 'name', 'phone', 'email', 'type', 'source', 'description', 'remarks', 'status', 'user_agent', 'ip_address', 'created_at');

        if ($request->get('data-show') == 'trashed') {
            $data['rows'] = $data['rows']->onlyTrashed();
            $data['is_trash'] = true;
        }
        else 
        {
            $data['rows'] = $data['rows']->where(function ($query) use ($request){

                if ($request->has('filter_name') && $request->get('filter_name')) {
                    $query->Where('name', 'like', '%'. $request->get('filter_name').'%');
                }
                if ($request->has('filter_phone') && $request->get('filter_phone')) {
                    $query->Where('phone', 'like', '%'. $request->get('filter_phone').'%');
                }
                if ($request->has('filter_email') && $request->get('filter_email')) {
                    $query->Where('email', 'like', '%'. $request->get('filter_email').'%');
                }
                if ($request->has('filter_type') && $request->get('filter_type') && $request->get('filter_type') !== 'all') {
                    $query->where('type', $request->get('filter_type'));
                }
                if ($request->has('filter_created_at') && $request->get('filter_created_at')) {
                    $query->where('created_at', 'like', $request->get('filter_created_at').'%');
                }
                if ($request->has('filter_status') && $request->get('filter_status') && $request->get('filter_status') !== 'all') {
                    $query->where('status', $request->get('filter_status') == 'new'?1:0);
                }

            });
            $data['is_trash'] = false;
        }
        $data['rows'] = $data['rows']->orderby('created_at', 'desc')->paginate($data['per_page']);

        $data['request'] = $request->all();
        $data['types']= ['' => 'All'];
        foreach(config('custom.feedbackTypes') as $key=>$feedback_types)
        {
            $data['types'][$key] = $feedback_types['name_np'];
        }

        return view(parent::loadDefaultDataToView($this->view_path.'.index'), compact('data'));
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
        $data['row'] = $this->model->withTrashed()->find($id);
        $data['reply']= FeedbackResult::where('feedback_id', $id)->orderBy('created_at', 'desc')->get();

        if (!$data['row']) {
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
        $data['activitylogs'] = ActivityLog::where('panel', $this->panel)->where('panel_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);

        return view(parent::loadDefaultDataToView($this->view_path.'.show'), compact('data'));
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
        $id = $request->get('key');
        $data = [];
        DB::beginTransaction();
        $data['row'] = $this->model->find($id);

        if (!$this->delete($data['row'])) {
            DB::rollback();
            $request->session()->flash('error_message', 'Invalid request.');
            return response($request);
        }

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('delete a '.$this->panel .' <a href="' . route($this->base_route . '.show', $id) . '">' . $data['row']->title_en . '</a>', $this->panel, $id, 'deleted');
        DB::commit();

        $request->session()->flash('success_message', $this->panel.' deleted successfully.');
        return response($request);
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
            DB::rollback();
            $request->session()->flash('error_message', 'Invalid request.');
            return redirect()->route($this->base_route.'.index');
        }
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
        return true;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function replySubmit(FeedbackReplyValidation $request)
    {
        abort_unless(\Gate::allows('reply-'.Str::lower($this->panel)), 403);
        DB::beginTransaction(); 

        $data['feedback_id'] = $request->feedback_id;
        $data['subject']     = $request->subject;
        $data['message']     = $request->message;
        $data['email']       = $request->email;
        $data['name']       = $request->name;
        $data['user_id']     = Auth::user()->id;
        $news = FeedbackResult::create($data);

        $send['user'] = Mail::send('emails.feedback_reply_sent', [ 'data' => $data ], function ($m) use ($data) {
                $m->from( config('myPath.mail_from'), config('myPath.mail_from_name') );
                $m->to( $data['email'], $data['name'] );
                $m->subject($data['subject']);
            });
        
        $updateReplied  =Feedback::where('id', $request->feedback_id)->update(['status'=>0]);

        if(config('custom.activity_log') == true)
            ActivityLog::makeActivity('reply '.$this->panel .' of  <a href="' . route($this->base_route . '.show', $request->feedback_id) . '">' . $request->get('name') . '</a>', $this->panel, $request->feedback_id, 'created');
        DB::commit();

        $request->session()->flash('success_message', $this->panel . ' replied successfully.');
        return redirect()->back();
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
                        case 'new':
                          //  $this->authorize('update', $this->model);
                            $row->status = 1;
                            $row->save();
                            $success_count++;
                            break;
                        case 'replied':
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

}
