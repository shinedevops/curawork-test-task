<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Request as Reqeusts};
use Auth;
use App\Traits\CommonData;

class HomeController extends Controller
{
    use CommonData;
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
    public function index(Request $request)
    {   
        // count all suggestions
        $suggestions = $this->getCount('suggestions');

        // count sent requests
        $sentRequests = $this->getCount('sentRequests');

        // count received requests
        $receivedRequests = $this->getCount('receivedRequests');

        // count connections
        $connections = $this->getCount('connections');
        if( $request->ajax() ){
            return compact('suggestions','sentRequests','receivedRequests','connections');
        }

        return view('home',compact('suggestions','sentRequests','receivedRequests','connections'));
    }
    
}
