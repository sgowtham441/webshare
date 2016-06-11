<?php

namespace App\Http\Controllers\Groupuser;

use App\Groupurl;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GroupuserController extends Controller
{
   
	/**
     * Show the user details.
     *
     * @return Response
     */
    public function getIndex()
    {
       return view('pages.groupend.welcome');
    }
    
    /**
     * Show the user details.
     *
     * @return Response
     */
    public function getUrlmanagement()
    {
    	$groupurls = Groupurl::where('user_id', Auth::id())->get();
    	return view('pages.groupend.urlmanagement',['groupurls' => $groupurls]);
    }
    
    public function getUrlgenerate(){
    	
    	$groupurls = Groupurl::where('user_id', Auth::id())->get();
    	$groupurlCount=$groupurls->count();
    	
    	while(1) {
    		for ($randomNumber = mt_rand(1, 9), $i = 1; $i < 10; $i++) {
    			$randomNumber .= mt_rand(0, 9);
    		}
    		$randomNumberCount= Groupurl::where('metting_id', $randomNumber)->count();
    		if($randomNumberCount < 1){
    			break;
    		}
    	}
    	$generatedUrl=url().'/meeting/'.$randomNumber;
    	
    	if($groupurlCount < Auth::user()->url_allowed){
    	Groupurl::create([
				'user_id' => Auth::id(),
    			'metting_id' => $randomNumber,
    			'url' => $generatedUrl
    	]);
    	}else{
    		return redirect('manage')->with('error', 'You url limit is over!');
    	}
		     return redirect('manage')->with('status', 'URL Generate successfully!');
    }

}
