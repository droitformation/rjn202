<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as Request;

use App\Droit\User\Worker\AboWorker;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Code\Worker\CodeWorkerInterface;

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

    protected $user;
    protected $code;
    protected $abo;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AboWorker $abo, UserInterface $user, CodeWorkerInterface $code)
    {
        $this->middleware('guest')->except('logout','getActivate');

        $this->abo  = $abo;
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCode()
    {
        return view('auth.code');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActivate()
    {
        return view('auth.activate');
    }


    /**
     * Handle a code request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCode(Request $request)
    {
        $code = $this->code->valid($request->input('code'));

        // Code is not valid
        if(!$code) {
            return redirect('code')->withInput($request->all())->with(['status' => 'danger', 'message' => 'Ce code n\'est pas valide']);
        }

        // Validate account credetials
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create new user
        $user = $this->user->create($request->all());

        // Update code and mark used by new user
        $this->code->markUsed($code->id,$user->id);

        // Login the user
        \Auth::login($user);

        return redirect()->intended('/')->with(['status' => 'success', 'message' => 'Votre compte sur rjne.ch est maintenant actif.']);
    }

    /**
     * Handle a reactivate request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postActivate(Request $request)
    {
        $code = $this->code->valid($request->input('code'));

        // Code is not valid
        if(!$code) {
            return redirect()->back()->withInput($request->all())->with(['status' => 'danger', 'message' => 'Ce code n\'est pas valide']);
        }

        if($this->attemptLogin($request))
        {
            $this->code->markUsed($code->id,\Auth::user()->id);

            return redirect()->intended('/')->with(['status' => 'success', 'message' => 'Votre compte sur rjne.ch est maintenant actif.']);
        }

        return redirect('activate')->withInput($request->all())->with(['status' => 'danger', 'message' => 'Ce compte n\'existe pas']);
    }
}
