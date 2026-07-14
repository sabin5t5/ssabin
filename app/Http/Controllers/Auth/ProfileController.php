<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\PasswordChangeRequest;
use Hash;
use Carbon\Carbon;
use Auth;
use App\Models\User;
use App\Http\Controllers\Admin\BaseController;

class ProfileController extends BaseController
{

    protected $base_route = 'profile';
    protected $view_path = 'auth.profile';
    protected $panel = 'Profile';

    public function index ()
    {
        $data = [];
        return view(parent::loadDefaultDataToView($this->view_path . '.change_password'), compact('data'));
    }
	/**
     * Show Password Form to change password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showPasswordForm()
    {

        $data = [];
        return view(parent::loadDefaultDataToView($this->view_path . '.change_password'), compact('data'));
    }

    /**
     * Handle a Change Password request to the application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(PasswordChangeRequest $request)
    {
        $hashed_password = Auth::user()->password;
        $plain_password  = $request->current_password;

        if (Hash::check($plain_password, $hashed_password))
        {
            $now = Carbon::now();
            $change_password = User::where('id', Auth::user()->id )
                            ->update([
                                      'password'   => bcrypt( $request->password),
                                      'last_password_change' => $now,
                                    ]);

        if($change_password)
            $request->session()->flash('success_message', 'Password updated!');
            return redirect()->route($this->base_route . '.index');
        }
        else{
            $request->session()->flash('error_message', 'The Current Password does not match!');
            return redirect()->route($this->base_route . '.index');
        }

    }
}