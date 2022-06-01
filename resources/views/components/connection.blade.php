@inject('GetCommonData', 'App\Traits\GetCommonData')
@if(isset($connections))
    @forelse ($connections as $connection)
        <div class="my-2 shadow text-white bg-dark p-1" id="">
            <div class="d-flex justify-content-between">
                <table class="ms-1">
                @if(Auth::user()->id == $connection->sent_by)
                    <td class="align-middle">{{ $connection->sentTo->name }}</td>
                @else
                    <td class="align-middle">{{ $connection->sentBy->name }}</td>
                @endif
                <td class="align-middle"> - </td>
                @if(Auth::user()->id == $connection->sent_by)
                    <td class="align-middle">{{ $connection->sentTo->email }}</td>
                @else
                    <td class="align-middle">{{ $connection->sentBy->email }}</td>
                @endif
                <td class="align-middle">
                </table>
                <div>
                @php 
                $mutualId = $connection->sent_by;
                if(Auth::user()->id == $connection->sent_by){
                    $mutualId = $connection->sent_to;
                }
                @endphp
                    <button style="width: 220px"  id="get_connections_in_common" class="btn btn-primary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse_{{$mutualId}}" aria-expanded="false" aria-controls="collapseExample">
                        Connections in common ({{count($GetCommonData->mutualFriends($mutualId))}})
                    </button>
                    <button id="create_request_btn_" class="btn btn-danger me-1 cancel_request_btn" data-id="{{$connection->id}}">Remove Connection</button>
                </div>
            </div>

            <div class="collapse" id="collapse_{{$mutualId}}">
                <div id="content_second" class="p-2">
                    @php 
                    $commonData = $GetCommonData->mutualFriends($mutualId)
                    @endphp
                    @if(isset($commonData))
                        @forelse ($commonData as $connect)
                        <div class="p-2 shadow rounded mt-2  text-white bg-dark">{{$connect->name}} - {{$connect->email}}</div>
                        @empty
                            <p>No Connections</p>
                        @endforelse
                    @endif
                </div>
            </div> 
        </div>
    @empty
        <p>No Connections</p>
    @endforelse
@endif
