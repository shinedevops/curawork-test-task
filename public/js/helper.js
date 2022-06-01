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
        getAjax('list-suggestions?page=' + page);
    });

    $('#get_suggestions_btn').click();

    /* When click on received requests */
    $('body').on('click', '#get_received_requests_btn', function () {
      var page = 1;
      $('#content').html('');
      getAjax('list-received-requests?=' + page);
    });

    /* When click on sent requests */
    $('body').on('click', '#get_sent_requests_btn', function () {
		var page = 1;
		$('#content').html('');
		getAjax('list-sent-requests?page=' + page);
    });

    /* When click on sent requests */
    $('body').on('click', '#get_connections_btn', function () {
      	var page = 1;
		$('#content').html('');
		getAjax('connections?page=' + page);
    });

    /* display skelton for different tabs */
    $('body').on('click', '#load_more_btn', function () {
        var active_tab = $(".btn-check:checked + .btn-outline-primary");
        switch( active_tab.data("tab-type") ){
			case("suggestion"):
				var hit_url = "list-suggestions";
			break;
			case("sent-request"):
				var hit_url = "list-sent-requests";
			break;
			case("received-requests"):
				var hit_url = "list-received-requests";
			break;
			case("connections"):
				var hit_url = "connections";
        }
        var page = active_tab.data("next-page");
        getAjax( hit_url + '?page=' + page);
    });
});

/* when click on connect button */
$('body').on('click', '.create_request_btn', function () {
    var user_id = $(this).data('id');
    postAjax('send-request',user_id, this);
	
});

// update tabs count
function updateButtonCount(){
	$.get('get-all-count', function (data) {
		console.log( data );
		$("#get_suggestions_btn").text( $("#get_suggestions_btn").text().replace(/\((.+?)\)/g, "("+ data.suggestions +")") );
		$("#get_sent_requests_btn").text( $("#get_sent_requests_btn").text().replace(/\((.+?)\)/g, "("+ data.sentRequests +")") );
		$("#get_received_requests_btn").text( $("#get_received_requests_btn").text().replace(/\((.+?)\)/g, "("+ data.receivedRequests +")") );
		$("#get_connections_btn").text( $("#get_connections_btn").text().replace(/\((.+?)\)/g, "("+ data.connections +")") );
	})
}

/* when click on accept request  */
$('body').on('click', '.accept_request_btn', function () {
    var id = $(this).data('id');
    $(this).parents('.my-2').remove();
    $.get('accept-request/' + id, function (data) {
		updateButtonCount();
	})
	
});

/* when click cancel request */

$('body').on('click', '.cancel_request_btn', function () {
	var id = $(this).data('id');
	$(this).parents('.my-2').remove();
	$.get('withdraw-request/' + id, function (data) {
		updateButtonCount();
	})
});

// common ajax get method
function getAjax(url,common = null) {
	$('#content').append( $("#connections_in_common_skeleton .px-2.common-skelton").clone() );
	$.get(url, function (data) {
		if( data.next_page ){
			$("#load_more_btn").data("next-page",data.next_page);
			$("#load_more_btn").show();
		}else{
			$("#load_more_btn").hide();
		}
		var active_tab = $(".btn-check:checked + .btn-outline-primary");
		active_tab.data("next-page",data.next_page);
		$('#content .px-2.common-skelton').remove();
		$('#content').append(data.data);
  	})
}
// common ajax post method
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
			updateButtonCount();
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