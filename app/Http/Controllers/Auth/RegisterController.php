<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'            => 'required|string|max:50',
            'middle_name'     => 'nullable|string|max:50',
            'last_name'       => 'required|string|max:50',

            'street_address'  => 'nullable|string|max:255',
            'city'            => 'required|string|max:100',
            'state_province'  => 'nullable|string|max:100',
            'postal_code'     => 'nullable|string|max:20',
            'country'         => ['required', 'string', 'size:2', Rule::in(array_keys(config('countries_phone')))],

            'country_code'    => ['required', 'string', 'max:6', Rule::in(array_column(config('countries'), 'code'))],
            'mobile'          => 'required|string|max:20',

            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name']. ' '. $data['middle_name']. ' '. $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('user');



        Person::create([
            'user_id' => $user->id,
            'first_name' => $data['name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'current_address' => $data['street_address']. ', '. $data['city']. ', '. $data['state_province']. ', '. $data['postal_code']. ', '. $data['country'],
            'mobile' => $data['country_code']. $data['mobile'],
            'email' => $data['email']
        ]);

        PersonAddress::create([
            'user_id' => $user->id,
            'address_type' => 'current',
            'street_address' => $data['street_address'],
            'city' => $data['city'],
            'state_province' => $data['state_province'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'is_primary' => true,
        ]);

        return $user;
    }
}
