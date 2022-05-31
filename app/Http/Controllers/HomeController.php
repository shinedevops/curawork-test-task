<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Request as Reqeusts};
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        // count all suggestions
        $suggestions = User::whereDoesntHave('sentRequests', function($q){
            $q->where('sent_to', '!=', Auth::user()->id);
        })->orWhereDoesntHave('receivedRequests', function($query){
            $query->where('sent_by', '!=', Auth::user()->id);
        })->count();

        // count sent requests
        $sentRequests = Auth::user()->sentUserRequests()->wherePivot('status', 0)->count();
        
        // count received requests
        $receivedRequests = Auth::user()->recievedUserRequests()->wherePivot('status', 0)->count();

        // count connections
        $connections = User::whereHas('sentRequests', function($q){
            $q->where('sent_to', Auth::user()->id)->where('status', 1);
        })->orWhereHas('receivedRequests', function($query){
            $query->where('sent_by', Auth::user()->id)->where('status', 1);
        })->count();
        return view('home',compact('suggestions','sentRequests','receivedRequests','connections'));
    }
}
