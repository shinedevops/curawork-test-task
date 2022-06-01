<?php
namespace App\Traits;
use App\Models\{User,Request as Reqeusts};
use Auth;
use Illuminate\Database\Eloquent\Builder;

trait CommonData{

  	//get common connections
    public function mutualFriends($id)
    {
        $profile = User::where('id', $id)->first();
        $profileFriends = $profile->sentUserRequests()->wherePivot('status', 1)->get();
        $profileFriend = $profile->recievedUserRequests()->wherePivot('status', 1)->get();
        $profileFriendsIds = [];
		foreach ($profileFriends as $entry){
		$profileFriendsIds[] = $entry->id;
		}
		foreach ($profileFriend as $entry){
			$profileFriendsIds[] = $entry->id;
		}
		$profileFriendsIds = array_unique($profileFriendsIds);
		
		$profile = User::where('id', Auth::id() )->first();
        $profileFriends = $profile->sentUserRequests()->wherePivot('status', 1)->get();
        $profileFriend = $profile->recievedUserRequests()->wherePivot('status', 1)->get();
        $loggedInUser = [];
		foreach ($profileFriends as $entry){
		$loggedInUser[] = $entry->id;
		}
		foreach ($profileFriend as $entry){
			$loggedInUser[] = $entry->id;
		}
		$loggedInUser = array_unique($loggedInUser);

		$profileFriendsIds = array_intersect( $profileFriendsIds , $loggedInUser );
        $loggedUserFriends = User::whereIn('id', $profileFriendsIds)->get();
		
        return $loggedUserFriends;
    }

    // count suggestions
    public function getCount($case)
    {
		switch($case) {
			case('suggestions'):
				$data = User::whereDoesntHave("sentRequests",function($query){
					$query->where('sent_by',Auth::id());
				})->count();
				break;

			case('sentRequests'):
				$data = Auth::user()->sentUserRequests()->wherePivot('status', 0)->count();
				break;

			case('receivedRequests'):
				$data = Auth::user()->recievedUserRequests()->wherePivot('status', 0)->count();
				break;

			case('connections'):
				$data = Reqeusts::where(function($q){$q->where('sent_by',  Auth::user()->id)->orWhere('sent_to',  Auth::user()->id); })->where('status', 1)->count();
				break;

			default:
				$data = User::whereDoesntHave('sentRequests', function($q){
					$q->where('sent_to', '!=', Auth::user()->id);
				})->orWhereDoesntHave('receivedRequests', function($query){
					$query->where('sent_by', '!=', Auth::user()->id);
				})->count();
				break;
		}
		return $data;
    }
}