<?php

namespace App\Http\Controllers;

use App\Groupurl;
use App\Onlineuser;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MeetingController extends Controller
{
   
	public static $joinrules= [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255'
	];
	
	/**
     * Show the user details.
     *
     * @return Response
     */
    public function getIndex($id)
    {
    	
    	$meetingId= Groupurl::where('metting_id', $id)->where('urlstatus', 1)->count();
    	if($meetingId==1){
    		if(Session::get('onlineuser')==$id){
    			return view('pages.viewscreen',['meetingid' => Session::get('onlineuser')]);
    		}else{
    		return view('pages.insertuser',['meetingid' => $id]);
    		}
    	}else{
    	return redirect('/')->with('error', 'Invalid URL!');
    	}
    }
 
    
    /**
     * Show the user details.
     *
     * @return Response
     */
    public function postJoin(Request $request,$id)
    {
    	 
    	
    	$data=$request->all();
    	$validator = Validator::make($data, static::$joinrules);
    	if($validator->fails()){
    		return redirect('meeting/'.$id)
    		->withErrors($validator)
    		->withInput();
    	}
    	$meetingId= Groupurl::where('metting_id', $id)->where('urlstatus', 1)->count();
    	if($meetingId==1){
    		
    		while(1) {
    			for ($randomNumber = mt_rand(1, 9), $i = 1; $i < 6; $i++) {
    				$randomNumber .= mt_rand(0, 9);
    			}
    			$meetingUserId=$id.$randomNumber;
    			$randomNumberCount=$meetingId= Onlineuser::where('metting_user_id', $meetingUserId)->where('metting_id', $id)->count();
    			if($randomNumberCount < 1){
    				break;
    			}
    		}
    		
    		if(Session::get('onlineuser')==$id){
    			return view('pages.viewscreen',['meetingid' => Session::get('onlineuser')]);
    		}
    		
    		Onlineuser::create([
    				'metting_id' => $id,
    				'metting_user_id' => $meetingUserId,	
    				'name' => $data['name'],
    				'email' => $data['email']
    		]);
    		
    		Session::set('onlineuser',$id);
    		Session::set('onlineuserid',$meetingUserId);
    		return redirect('viewscreen')->with('status', 'Welcome to Screen Share!');
    	}else{
    		return redirect('/')->with('error', 'Invalid URL!');
    	}
    }
 
    /**
     * Show the user details.
     *
     * @return Response
     */
    public function getViewscreen()
    {
        $mettingSessionId=Session::get('onlineuser');
    	$meetingId= Groupurl::where('metting_id', $mettingSessionId)->where('urlstatus', 1)->count();
    	if($meetingId==1){
    		$onlineUser=Onlineuser::where('metting_user_id', Session::get('onlineuserid'))->where('metting_id', $mettingSessionId)->get();
    		return view('pages.viewscreen',['onlineuser' => $onlineUser]);
    	}else{
    		return redirect('meeting/'.$mettingSessionId)->with('error', 'Invalid Access!');
    	}
    }
    
    public function getViewerscreen(){
    	
    	$mettingSessionId=Session::get('onlineuser');
    	$meetingId= Groupurl::where('metting_id', $mettingSessionId)->where('urlstatus', 1)->count();
    	if($meetingId==1){
    		$onlineUser=Onlineuser::where('metting_user_id', Session::get('onlineuserid'))->where('metting_id', $mettingSessionId)->first();
    		$onlineuserList=Onlineuser::where('metting_id', $mettingSessionId)->metting_id->toArray();
    		return view('pages.viewer',['onlineuser' => $onlineUser,'users'=>$onlineuserList]);
    	}else{
    		return redirect('meeting/'.$mettingSessionId)->with('error', 'Invalid Access!');
    	}
    	
    }
    
    /**
     * Show the user details.
     *
     * @return Response
     */
    public function getScreenlogout()
    {
    	Session::flush();
    		return redirect('/');
    	
    }
    
}
