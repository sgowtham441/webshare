<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsermanagementController extends Controller
{
    
	public static $passwordrules = [
			'password' => 'required|confirmed|min:6|confirmed'
	];
	
	public static $profilerules= [
			'name' => 'required|max:255',
			'url_allowed' => 'required|max:10|integer',
			'user_per_url' => 'required|max:10|integer'
	];
	
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6|confirmed',
            'url_allowed' => 'required|max:10|integer',
            'user_per_url' => 'required|max:10|integer',
        ]);
    }
    /**
     * Show the user details.
     *
     * @return Response
     */
    public function getIndex()
    {
       $users = User::where('role', '!=' , 1)->get();
       return view('pages.adminend.usermanagement',['users' => $users]);
    }

    /**
     * Show the add form.
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getAdd() {
      return view('pages.adminend.adduser');
    }

    /**
     * Show the edit form.
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getEdit($id) {
      return view('pages.adminend.useredit',['user' => User::find($id)]);
    }

    /**
     * Delete User
     * @param int $id
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function getDelete($id){
       $users = User::destroy($id);
       return redirect('ALadmin/user/')->with('status', 'User delete successfully!');
    }

    /**
     * Show the change password.
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getChangepassword($id) {
    	return view('pages.adminend.userchangepassword',['user' => User::find($id)]);
    }

    /**
     * Show the user details.
     *
     * @return Response
     */
    public function postSaveuser(Request $request)
    {
        $data=$request->all();
        $validator=$this->validator($data);
       if($validator->fails()){
          return redirect('ALadmin/user/add')
                        ->withErrors($validator)
                        ->withInput();
       }
       User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        	'url_allowed' => $data['url_allowed'],
        	'user_per_url' => $data['user_per_url'],
        	'role' => 2,
        ]);
       return redirect('ALadmin/user')->with('status', 'User create successfully!');
    }
    
    /**
     * Show the user details.
     *
     * @return Response
     */
    public function postSavepassowrd(Request $request,$id)
    {
    	$data=$request->all();
    	$validator = Validator::make($data, static::$passwordrules);
    	if($validator->fails()){
    		return redirect('ALadmin/user/changepassword/'.$id)
    		->withErrors($validator)
    		->withInput();
    	}
    	$user = User::find($id);
    	$user->password = bcrypt($data['password']);
    	$user->save();
    	
    	return redirect('ALadmin/user/')->with('status', 'Password changed successfully!');
    }

 /**
     * Show the user details.
     *
     * @return Response
     */
    public function postSaveprofile(Request $request,$id)
    {
    	$data=$request->all();
    	$validator = Validator::make($data, static::$profilerules);
    	if($validator->fails()){
    		return redirect('ALadmin/user/changepassword/'.$id)
    		->withErrors($validator)
    		->withInput();
    	}
    	$user = User::find($id);
    	$user->name = $data['name'];
    	$user->url_allowed = $data['url_allowed'];
    	$user->user_per_url = $data['user_per_url'];
    	$user->save();
    	
    	return redirect('ALadmin/user/')->with('status', 'Profile Update successfully!');
    }
}
