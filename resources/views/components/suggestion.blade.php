@forelse ($suggestions as $suggestion)
<div class="my-2 shadow  text-white bg-dark p-1" id="">
  <div class="d-flex justify-content-between {{ $suggestion->recievedUserRequests->pluck('id') }}">
    <table class="ms-1">
        <td class="align-middle">{{ $suggestion->name }}</td>
        <td class="align-middle"> - </td>
        <td class="align-middle">{{ $suggestion->email }}</td>
        <td class="align-middle">  
    </table>
    <div>
      <button id="create_request_btn_"  data-id="{{$suggestion->id}}" class="btn btn-primary me-1 create_request_btn">Connect</button>
    </div>
  </div>
</div>

@empty
  <p>No users</p>
@endforelse

