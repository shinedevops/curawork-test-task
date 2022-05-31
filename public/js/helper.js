$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /* When click on suggestions */
    $('body').on('click', '#get_suggestions_btn', function () {
        var page = 1;
        $('#content').html('');
        // var user_id = $(this).data('id');
        getAjax('list-suggestions?page=' + page);
    });

    /* When click on received requests */
    $('body').on('click', '#get_received_requests_btn', function () {
      var page = 1;
      $('#content').html('');
      // var user_id = $(this).data('id');
      getAjax('list-received-requests?=' + page);
    });

    /* When click on sent requests */
    $('body').on('click', '#get_sent_requests_btn', function () {
      var page = 1;
      $('#content').html('');
      // var user_id = $(this).data('id');
      getAjax('list-sent-requests?page=' + page);
    });

    /* When click on sent requests */
    $('body').on('click', '#get_connections_btn', function () {
      var page = 1;
      $('#content').html('');
      // var user_id = $(this).data('id');
      getAjax('connections?page=' + page);
    });

    $('body').on('click', '#load_more_btn', function () {
        var page = $(this).data("next-page");
        // var user_id = $(this).data('id');
        getAjax('list-suggestions?page=' + page);
        
    });
});

// when click on connect button
$('body').on('click', '.create_request_btn', function () {
    var user_id = $(this).data('id');
    postAjax('send-request',user_id, this);
});
// when click on accept request 
$('body').on('click', '.accept_request_btn', function () {
    var id = $(this).data('id');
    $.get('accept-request/' + id, function (data) {
        $(this).parents('.my-2').remove();
    })
});

// when click cancel request
$('body').on('click', '.cancel_request_btn', function () {
  var id = $(this).data('id');
  $.get('withdraw-request/' + id, function (data) {
    $(this).parents('.my-2').remove();
  })
});

function getAjax(url) {
  $('#content').append( $("#connections_in_common_skeleton .px-2.common-skelton").clone() );
  $.get(url, function (data) {
    if( data.next_page ){
      $("#load_more_btn").data("next-page",data.next_page);
    }else{
      $("#load_more_btn").hide();
    }
    $('#content .px-2.common-skelton').remove();
    $('#content').append(data.data);
  })
}
function postAjax(url, userId, that){
  $.ajax({
    type: "POST",
    dataType: "json",
    url: url,
    data: {'id': userId},
    success: function(data){					
      if(data.success){
        $(that).parents('.my-2').remove();
      }
    }
  });
}

function ajaxForm(formItems) {
  var form = new FormData();
  formItems.forEach(formItem => {
    form.append(formItem[0], formItem[1]);
  });
  return form;
}



/**
 * 
 * @param {*} url route
 * @param {*} method POST or GET 
 * @param {*} functionsOnSuccess Array of functions that should be called after ajax
 * @param {*} form for POST request
 */
function ajax(url, method, functionsOnSuccess, form) {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })

  if (typeof form === 'undefined') {
    form = new FormData;
  }

  if (typeof functionsOnSuccess === 'undefined') {
    functionsOnSuccess = [];
  }

  $.ajax({
    url: url,
    type: method,
    async: true,
    data: form,
    processData: false,
    contentType: false,
    dataType: 'json',
    error: function(xhr, textStatus, error) {
      console.log(xhr.responseText);
      console.log(xhr.statusText);
      console.log(textStatus);
      console.log(error);
    },
    success: function(response) {
      for (var j = 0; j < functionsOnSuccess.length; j++) {
        for (var i = 0; i < functionsOnSuccess[j][1].length; i++) {
          if (functionsOnSuccess[j][1][i] == "response") {
            functionsOnSuccess[j][1][i] = response;
          }
        }
        functionsOnSuccess[j][0].apply(this, functionsOnSuccess[j][1]);
      }
    }
  });
}


function exampleUseOfAjaxFunction(exampleVariable) {
  // show skeletons
  // hide content

  var form = ajaxForm([
    ['exampleVariable', exampleVariable],
  ]);

  var functionsOnSuccess = [
    [exampleOnSuccessFunction, [exampleVariable, 'response']]
  ];

  // POST 
  ajax('/example_route', 'POST', functionsOnSuccess, form);

  // GET
  ajax('/example_route/' + exampleVariable, 'POST', functionsOnSuccess);
}

function exampleOnSuccessFunction(exampleVariable, response) {
  // hide skeletons
  // show content

  console.log(exampleVariable);
  console.table(response);
  $('#content').html(response['content']);
}