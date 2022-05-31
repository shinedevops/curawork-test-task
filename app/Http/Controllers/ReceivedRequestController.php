<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Request as Reqeusts};
use Auth;

class ReceivedRequestController extends Controller
{
    /**
     * To get list all received requests 
    */
    public function index()
    {
        $requestData = Auth::user()->recievedUserRequests()->wherePivot('status', 0)->paginate(10);
        $RequestDetail =  view('components.request')->with(['requestData'=> $requestData, 'mode' => 'received'])->render();

        return response()->json(['success' => true, 'data' => $RequestDetail , 'next_page' => $requestData->hasMorePages() ? $requestData->currentPage() + 1  : null ]);
    }
    /**
     * To withdraw sent request
    */
    public function update($id){
        Reqeusts::where('id', $id)->update(['status' => 1]);

        return response()->json(['success' => true, 'data' => null]);
    }
}
