@if(isset($requestData))
@forelse ($requestData as $request)
<div class="my-2 shadow text-white bg-dark p-1" id="">
  <div class="d-flex justify-content-between">
    <table class="ms-1">
      <td class="align-middle">{{ $request->name }}</td>
      <td class="align-middle"> - </td>
      <td class="align-middle">{{ $request->email }}</td>
      <td class="align-middle">
    </table>
    <div>
      @if ($mode == 'sent')
        <button id="cancel_request_btn_" data-id="{{$request->pivot->id}}" class="btn btn-danger me-1 cancel_request_btn"
          onclick="">Withdraw Request</button>
      @else
        <button id="accept_request_btn_" data-id="{{$request->pivot->id}}" class="btn btn-primary me-1 accept_request_btn"
          onclick="">Accept</button>
      @endif
    </div>
  </div>
</div>
@empty
  <p>No requests</p>
@endforelse
@endif
