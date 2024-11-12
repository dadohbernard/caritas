<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use Exception;
use Password;
use App\Events\ResetPasswordEvent;
use Illuminate\Auth\Events\PasswordReset;
use App\Rules\MatchOldPassword;
use Spatie\Activitylog\Facades\Activity;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class AuthController extends Controller
{
    // public function __construct()
    // {
    //    $this->middleware('auth', ['except' => ['index', 'login','register','forgotPassword','store','viewReset','reset','storePassword']]);
    // }
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('log', only: ['index', ]),
            new Middleware('subscribed', except: ['login','register','forgotPassword','store','viewReset','reset','storePassword']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
        //
    }

 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        try{

            $validator = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required',
            ]
        );

            $credentials = $request->only('email', 'password');


            if (Auth::attempt($credentials)) {
                // if(Auth::user()-> role == "Admin"){
                if(Auth::user()->status == 1 && Auth::user()->is_delete == 0){

                    activity()
                    ->performedOn(User::find(Auth::user()->id))
                    ->withProperties(['by'=>User::find(Auth::user()->id)])
                    ->causedBy(User::find(Auth::user()->id))
                    ->event("Logged in")
                    ->log('User logged in');
                    return redirect()->intended('dashboard')
                    ->withSuccess('Signed in');

                }else{
                    $request->session()->flash('error', "Your account blocked,Please contact your admin");
                return redirect(route('login'));
                }

            // }else{
            //     $request->session()->flash('error', "You don't have access on admin panel");
            //     return redirect(route('login'));
            // }
        }
        else{
                $request->session()->flash('error', "Login details are not valid");
                return redirect(route('login'));
            }

        }
        catch(Exception $e)
        {

        }
        //
    }
     /**
     * This function is used to return forgot password form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function forgotPassword()
    {
        return view('auth.forgot-password');
        //
    }
/**
     * This function is used to return form via on your email account
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function viewReset(Request $request)
    {


        $data['email'] = $request->email;
        $data['token'] = $request->token;
        return view('auth.reset-password',$data);
    }
    /**
     * This function is used to return form via on your email account
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function Reset(Request $request)
    {

        $data['email'] = $request->email;
        $data['token'] = $request->token;
        $data['users'] = User::where('id', $request->id)->get();
        return view('auth.reset',$data);
    }

/**
     * This function is used to store password
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */

    public function storePassword(Request $request)
    {
        try{
        $validator = \Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed'],
        ]);
        if ($validator->fails()) {
                    return back()->withErrors($validator);
        }
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    // 'account_verified'=>1,
                    'password' => \Hash::make($request->password),
                    'remember_token' => \Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
       if($status == Password::PASSWORD_RESET)
       {
           $request->session()->flash('success', 'Please login again with updated password');
           return redirect()->route('login');
       }
       return back()->withInput($request->only('email'))
       ->withErrors(['email' => __($status)]);
    }
    catch(Exception $e)
    {
        return back()->withErrors(['errors' => 'Something went wrong '.$e->getMessage()]);
    }
    }
    /**
     * This function is used to store password
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */

    public function storePasswordReset(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validator->fails()) {
                        return redirect(route('forgot-password'))
                        ->withErrors($validator)
                        ->withInput();
            }
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if($user == null)
            {
                $request->session()->flash('error', "Email is not exist in database");
                return redirect(route('forgot-password'));
            }
            event(new ResetPasswordEvent($email));
            $request->session()->flash('link_sent', "Reset link is successfully sent");
            return response()->json(['status' => 200,'message' => "Other cert Item add",'data'=>$user]);
         }
        catch(Exception $e)
        {

        }
    }
   /**
     * This function is used to send link on email
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */

    public function store(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validator->fails()) {
                        return redirect(route('forgot-password'))
                        ->withErrors($validator)
                        ->withInput();
            }
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if($user == null)
            {
                $request->session()->flash('error', "Email is not exist in database");
                return redirect(route('forgot-password'));
            }
            event(new ResetPasswordEvent($email));
            $request->session()->flash('link_sent', "Reset link is successfully sent");
                return redirect(route('forgot-password'));
         }
        catch(Exception $e)
        {

        }
        //
    }
/**
     * This function is used to return change password form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */

    public function viewChangePassword(Request $request)
    {
         $data['title'] = "Change password";
        return view('manage-users.change_password', $data);
    }

    /**
     * This function is used to store new password as changed from admin
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */

    public function storeNewPassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        if ($validator->fails()) {
                    redirect(route('change-password'))
                    ->withErrors($validator)
                    ->withInput();
        return response()
                  ->json(['status' => 422, 'message' => ""]);
        }
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        $request->session()
        ->flash('success', "Password is successfully updated");
    return response()
        ->json(['status' => 200, 'message' => "Password is successfully updated"]);
    }

}
