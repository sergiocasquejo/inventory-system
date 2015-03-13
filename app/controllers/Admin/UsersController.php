<?php namespace Admin;

class UsersController extends \BaseController {

	public function __construct() {

        $this->beforeFilter('owner', array('only' => array('index', 'create', 'edit', 'update')));
        $this->beforeFilter('profileOwner', array('only' => array('show')));
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$input = \Input::all();


		$users = \User::withTrashed()->search($input)->orderBy('id', 'desc')->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \User::withTrashed()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');
		return \View::make('admin.user.index')
			->with('users', $users)
			->with('appends', $appends)
			->with('totalRows', $totalRows);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		return \View::make('admin.user.create')
			->with('branches', array_add(\Branch::dropdown()->lists('name', 'id'), '', 'Select Branch'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();

		$rules = \User::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$user = new \User;

				if ($user->doSave($user, $input)) {
					$this->upload($user->id);
					
					return \Redirect::route('admin_users.index')->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($user->errors())->withInput();
			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput();
			}
		}
	}


	public function show($id) {
		$user = \User::findOrFail($id);
		return \View::make('admin.user.show')->with('user', $user);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		try {
			$user = \User::findOrFail($id);
		} catch(\Exception $e) {
			return \Redirect::back()->with('info', \Lang::get('agrivate.errors.restore'));
		}

		return \View::make('admin.user.edit')
		->with('user', $user)
		->with('branches', array_add(\Branch::dropdown()->lists('name', 'id'), '', 'Select Branch'));
	}

	

	public function upload($id) {
		if (\Input::hasFile('photo')) {
			$avatar = \Config::get('agrivate.avatar');
			$fileName = $avatar['filename'];
			$fileExtension = $avatar['extension'];

			$fileNameWithExtension = $fileName.$fileExtension;

			$file = \Input::file('photo');
			$uploadPath = public_path('assets/uploads/'.$id.'/');

			$file->move($uploadPath, $fileNameWithExtension);	
			
			foreach ($avatar['sizes'] as $name => $size) {
				
				$image = \Image::make($uploadPath.$fileNameWithExtension)
				->resize($size[0], $size[1])->save($uploadPath.$fileName.'_'.$name.$fileExtension);
			}
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{


		$input = \Input::all();

		$rules = \User::$rules;

		$rules['password'] = 'alpha_dash';
		$rules['email'] = 'required|email|unique:users,email,'.$id.',id';

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				
				$user = \User::findOrFail($id);

					
				if ($user->doSave($user, $input)) {
					
					$this->upload($id);
					//route('admin_users.index')
					return \Redirect::back()->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($user->errors())->withInput();
			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput();
			}
		}
	}


	


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if (\User::withTrashed()->where('id', $id)->first()->is_admin == 1) {
			return \Redirect::back()->with('warning', \Lang::get('agrivate.errors.delete_owner_permission'));
		}


		$user = \User::withTrashed()->where('id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($user->trashed() || \Input::get('remove') == 1) {
            $user->forceDelete();
            $message = \Lang::get('agrivate.deleted');
        } else {
            $user->delete();
        }

        // Session::set('success', 'Successfully deleted');
        return \Redirect::route('admin_users.index')->with('success', $message);
        
	}


	/**
	 * Restore deleted resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function restore($id) {

		


		$user = \User::withTrashed()->where('id', $id)->first();
		if (!$user->restore()) {
			return \Redirect::back()->withErrors($user->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivate.restored'));
	}


}
