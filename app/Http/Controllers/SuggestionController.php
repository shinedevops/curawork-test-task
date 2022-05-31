<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Request as Reqeusts};
use Auth;

class SuggestionController extends Controller
{
    /**
     * To get list all suggestions 
    */
    public function index()
    {
        $suggestions = User::whereDoesntHave('sentRequests', function($q){
            $q->where('sent_to', '!=', Auth::user()->id);
        })->orWhereDoesntHave('receivedRequests', function($query){
            $query->where('sent_by', '!=', Auth::user()->id);
        })->paginate(10);

        $suggestionDetail =  view('components.suggestion')->with('suggestions', $suggestions)->render();

        return response()->json(['success' => true, 'data' => $suggestionDetail , 'next_page' => $suggestions->hasMorePages() ? $suggestions->currentPage() + 1  : null ]);
    }

    /**
     * To send request 
    */
    public function create(Request $request)
    {
        if($request->id != ""){
            $suggestions = Reqeusts::create(['sent_to' => $request->id, 'sent_by' => Auth::user()->id]);
            return response()->json(['success' => true, 'data' => null]);
        }
        return response()->json(['success' => false, 'data' => null]);
    }
}
