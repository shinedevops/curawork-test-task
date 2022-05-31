<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{User,Request as Reqeusts};
use Auth;

class ConnectionController extends Controller
{
    /**
     * To get list all received requests 
    */
    public function index()
    {
        // get connections
        $connections = User::whereHas('sentRequests', function($q){
            $q->where('sent_to', Auth::user()->id)->where('status', 1);
        })->orWhereHas('receivedRequests', function($query){
            $query->where('sent_by', Auth::user()->id)->where('status', 1);
        })->paginate(10);

      
        $RequestDetail =  view('components.connection')->with('connections', $connections)->render();

        return response()->json(['success' => true, 'data' => $RequestDetail , 'next_page' => $connections->hasMorePages() ? $connections->currentPage() + 1  : null ]);
    }
}
