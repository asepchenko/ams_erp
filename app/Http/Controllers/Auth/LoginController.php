<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*public function username()
    {
        return 'nik';
    }*/

    public function authenticated()
    {
        $url = auth()->user()->default_url;
        return redirect($url);
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'nik' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->nik, FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';
        if (auth()->attempt(array($fieldType => $input['nik'], 'password' => $input['password']))) {
            //return redirect()->route('home');
            $test = DB::select("select status from dt_region where nama_region = 'pu'");
            session()->put('flag', $test[0]->status);
            return $this->authenticated();
        } else {
            return redirect()->route('login')->with('error', 'Email-Address And Password Are Wrong.');
        }
    }
    /*public function authenticate(Request $request)
    {
        $credentials = $request->only('nik', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            //return redirect()->intended('dashboard');
            authenticated();
        }else{
            Redirect::back()->withErrors(['msg', 'Username and Password salah']);
        }
    }*/
}
