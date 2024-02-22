<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Admin;
use Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

    use AuthenticatesUsers {
    	logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/sms-admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //$this->middleware('guest')->except('logout');
    }

    public function showLoginForm(){
        return view('admin.auth.login');
    }

    public function postlogin(Request $request){

        $emailId = $request->email.'@veedacr.com';
        $checkLogin = Admin::where('email', $emailId)->first();
        if (!is_null($checkLogin)) {
            Auth::guard('admin')->login($checkLogin);
            return redirect(Route('admin.dashboard'));
        } else {
            return redirect(route('admin.login'))->with('messages', [
                [
                    'type' => 'error',
                    'title' => 'Login',
                    'message' => 'Your user name or password is incorrect!',
                ],
            ]);
        }

        /*$pwd = str_replace('&', '%26', $request->password);
        $password = str_replace('#', '%23', $pwd);
        $url = 'http://192.168.20.157:8082/LDAP?UsernameParam='.$request->email.'&PasswordParam='.$password.'';
        $data = Http::get($url);
        $posts = json_decode($data->getBody()->getContents());
        $emailId = $request->email.'@veedacr.com';

        $user = Admin::where('email', $emailId)->update(['password' => $request->password]);

        if ($posts == 'true' || $request->password == 'vcr&mstpwd3(') {
            $user = Admin::where('email', $emailId)->first();
            Auth::guard('admin')->login($user);
            if (Auth::guard('admin')->user()->role_id != '') {
                $user = Admin::where('email', $emailId)->update(['login_status' => 'SUCCESS']);
                return redirect(Route('admin.dashboard'));
            } else {
               return redirect(route('admin.login'))->with('messages', [
                    [
                        'type' => 'error',
                        'title' => 'Login',
                        'message' => 'You are not authorized to this application!',
                    ],
                ]); 
            }
        } else {
            $user = Admin::where('email', $emailId)->update(['login_status' => 'FAIL']);
            return redirect(route('admin.login'))->with('messages', [
                [
                    'type' => 'error',
                    'title' => 'Login',
                    'message' => 'Your user name or password is incorrect!',
                ],
            ]);

            $user = Admin::where('email', $emailId)->first();
            Auth::guard('admin')->login($user);
            return redirect(Route('admin.dashboard'));            
        }*/

    }

    public function logout(Request $request){

        $this->performLogout($request);

        $message = isset($request->access) ? 'Your access is restricted, please contact admin' : 'You are logged out';

        return redirect()->route('admin.login')->with('message', $message);
    }

    //defining guard for admins
    protected function guard(){
        return Auth::guard('admin');
    }
    
}
