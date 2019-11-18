<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Rol;
use Auth;
use Session;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/home');
    }

    public function login(Request $request) //Pendiente de revisión y mejora
    {
        $usuario = User::where('email', $request->input('email'))->first();

        if ($usuario)
        {
            //dd($usuario);
            $fecha = substr(now(), 0, 19);

            if ($usuario->user_estado == 1 )
            {
                $credenciales = $request->only('email', 'password');

                if (Auth::attempt($credenciales))
                {

                    //credenciales correctas

                    Auth::login($usuario, true);

                    Session::put('activo', true);

                    return redirect('home');

                } else {
                    //credenciales incorrectas
                    flash('Datos ingresados no son válidos.')->error();
                    return redirect('/home');
                }

            } else {
                flash('Usuario inactivo, por favor contacte con el administrador.')->error();
                return redirect('/home');

            }
        }

         else{
            //el email del usuario no se encuentra en la BD
            flash('Datos ingresados no son válidos.')->error();
            return redirect('/home');
         }
    }
}
