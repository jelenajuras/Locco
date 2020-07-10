<?php

namespace App\Http\Controllers\Auth;

use Sentinel;
use Redirect;
use Session;
use Centaur\AuthManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Centaur\Dispatches\BaseDispatch;
use Cartalyst\Sentinel\Users\UserInterface;
class SessionController extends Controller
{
    /** @var Centaur\AuthManager */
    protected $authManager;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthManager $authManager)
    {
        $this->middleware('sentinel.guest', ['except' => 'getLogout']);
        $this->authManager = $authManager;
    }

    /**
     * Show the Login Form
     * @return View
     */
    public function getLogin()
    {
		return view('auth.login');
    }

    /**
     * Handle a Login Request
     * @return Response|Redirect
     */
    public function postLogin(Request $request)
    {
		// Validate the Form Data
        $result = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Assemble Login Credentials
        $credentials = [
            'email' => trim($request->get('email')),
            'password' => $request->get('password'),
        ];
		
		Sentinel::authenticate($credentials);

		$user = Sentinel::getUser();
		// Return the appropriate response
        if(Sentinel::check())  {
			Sentinel::loginAndRemember($user);
			return Redirect::to('home');
        }	

		$message = "Korisničko ime ili lozinka nisu ispravni!";
		
		return Redirect::to('auth.login.form')->with('message', $message);
    }

    /**
     * Handle a Logout Request
     * @return Response|Redirect
     */
    public function getLogout(Request $request)
    {
        // Terminate the user's current session.  Passing true as the
        // second parameter kills all of the user's active sessions.
        $result = $this->authManager->logout(null, null);

        // Return the appropriate response
        return $result->dispatch(route('auth.login.form'));
    }
}
