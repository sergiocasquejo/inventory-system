<?php namespace Admin;


class AdminController extends \BaseController {

	public function login() {

		if (\Confide::user()) {
            return \Redirect::to('/admin/dashboard');
        } else {
            return \View::make('auth.login');
        }
	}


	public function doLogin() {
		$repo = \App::make('UserRepository');
        $input = \Input::all();

        if ($repo->login($input)) {
            return \Redirect::intended('/admin/dashboard');
        } else {
            if ($repo->isThrottled($input)) {
                $err_msg = \Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif ($repo->existsButNotConfirmed($input)) {
                $err_msg = \Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = \Lang::get('confide::confide.alerts.wrong_credentials');
            }

            return \Redirect::route('admin_login')
                ->withInput(\Input::except('password'))
                ->with('error', $err_msg);
        }
	}


    /**
     * Log the user out of the application.
     *
     * @return  Illuminate\Http\Response
     */
    public function logout()
    {
        \Confide::logout();

        return \Redirect::route('admin_login');
    }
}