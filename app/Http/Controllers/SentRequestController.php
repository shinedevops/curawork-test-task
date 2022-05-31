<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Request as Reqeusts};
use Auth;

class SentRequestController extends Controller
{
    /**
     * To get list all sent requests 
    */
    public function index()
    {
        $requestData = Auth::user()->sentUserRequests()->wherePivot('status', 0)->paginate(10);
        $RequestDetail =  view('components.request')->with(['requestData'=> $requestData, 'mode' => 'sent'])->render();

        return response()->json(['success' => true, 'data' => $RequestDetail , 'next_page' => $requestData->hasMorePages() ? $requestData->currentPage() + 1  : null ]);
    }
    /**
     * To withdraw sent request
    */
    public function destroy($id){
        Reqeusts::where('id', $id)->delete();

        return response()->json(['success' => true, 'data' => null]);
    }
}
