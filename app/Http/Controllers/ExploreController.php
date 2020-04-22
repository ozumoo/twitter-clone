<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function __invoke()
    {
    	$users = User::with('tweets')
    		->whereNotIn(
    			'id' , auth()->user()->follows()->pluck('id')
    			->prepend(auth()->user()->id)
    		)
    		->paginate(15);

    	return view('explore' , ['users' =>  $users]);
    }	
}
