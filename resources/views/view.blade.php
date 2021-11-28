<!DOCTYPE html>
<html>
<head>
    <title>View Events</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="{{ url('/') }}/vendor/datatables/buttons.server-side.js"></script>

</head>
<body>
      
<div class="container">

    <input type="hidden" id="addUrl" value="{{ url('event/add') }}" />
    <input type="hidden" id="updateUrl" value="{{ url('event/update') }}" />
    <input type="hidden" id="deleteUrl" value="{{ url('event/delete') }}" />
    <input type="hidden" id="exportUrl" value="{{ url('event/export') }}" />

	<div id="add_event_div" class="add_event d-none">
	    <h1>Event Form</h1>
	    <form method="post" id="addEventForm" action="{{ url('event/add') }}" enctype="multipart/form-data">

            <!-- CROSS Site Request Forgery Protection -->
            @csrf

            <input type="hidden" id="event_id" name="event_id" value="" />

            <div class="form-group">
                <label>Event Name</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>

            <div class="form-group">
                <label>Event Location</label>
                <textarea class="form-control" name="location" id="location" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Event Start Date</label>
                <input type="text" class="form-control datepicker" name="start_date" id="start_date" readonly="">
            </div>

            <div class="form-group">
                <label>Event End Date</label>
                <input type="text" class="form-control datepicker" name="end_date" id="end_date" readonly="">
            </div>

            <div class="form-group">
                <label>Event Banner Image</label>
                <input type="file" class="form-control" name="image" id="image">

                <div class="m-5" id="banner_img_div">
                    <img scr="" id="banner_image"  width="50%" height="100%" alt="Banner Image" />                    
                </div>
            </div>

            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
            <input type="reset" name="reset" value="Reset" class="btn btn-danger" id="show_events">
        </form>
	</div>

	<div id="list_of_events_div" class="list_of_events">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block" role="alert">
            <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
                <strong>{{ $message }}</strong>
        </div>
        @endif


        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block" role="alert">
            <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
                <strong>{{ $message }}</strong>
        </div>
        @endif

	    <h1>List Of Events</h1>

        <button class="btn btn-primary float-left mr-2 validButtons" id="export_event" disabled="">Export</button>
        <button class="btn btn-info float-left validButtons" id="delete_event" disabled="">Delete</button>

	    <button class="btn btn-success float-right" id="add_event">Add Event</button>
	    <br/><br/>
	    <!-- <table class="table table-bordered data-table" id="table">
	        <thead>
	            <tr>
	                <th>#</th>
	                <th>Banner Image</th>
	                <th>Name</th>
                    <th>Location</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
	            </tr>
	        </thead>
	    </table> -->
        {!! $dataTable->table() !!}
	</div>
</div>
     
</body>
     
<script type="text/javascript">
    function handlyShowHideDiv(type) {
    	if(type == 'add_event') {
            $("#addEventForm").attr('action', $("#addUrl").val());

	    	$("#add_event_div").removeClass('d-none')
	    	$("#list_of_events_div").addClass('d-none')    	    		
    	} else if(type == 'edit_event') {
            $("#addEventForm").attr('action', $("#updateUrl").val());
            
            $("#add_event_div").removeClass('d-none')
            $("#list_of_events_div").addClass('d-none')                 
        } else if(type == 'list_of_events')  {
	    	$("#list_of_events_div").removeClass('d-none')
	    	$("#add_event_div").addClass('d-none')    		
    	}
    }

    $(document).on('click', '.event.edit', function() {
        var currentRow = JSON.parse($(this).attr('data-currentRow'));

        $("#event_id").val(currentRow.id);
        $("#name").val(currentRow.name);
        $("#location").val(currentRow.location);
        $("#start_date").val(currentRow.start_date_formatted);
        $("#end_date").val(currentRow.end_date_formatted);
        $("#banner_image").attr('src', currentRow.banner_image);
        $("#banner_img_div").show();

    	handlyShowHideDiv('edit_event');
    })

    $(document).on('click', '.event.delete', function() {
        var currentRowId = $(this).attr('data-currentRowId');

        if (confirm('Are you sure you want to delete the event?')) {
            window.location.href = $("#deleteUrl").val() + '?event_id=' + currentRowId;
        } else {
          // Do nothing!
        }
    })

    $(document).on('change', ".checkbox", function() {
        var selectedEventIds = [];
        $('.checkbox:checked').each(function() {
            selectedEventIds.push($(this).val());
        });
        
        if(selectedEventIds.length > 0) 
            $('.validButtons').attr('disabled', false);
        else            
            $('.validButtons').attr('disabled', true);
    });

    $("#delete_event").on('click', function() {
        var selectedEventIds = [];
        $('.checkbox:checked').each(function() {
            selectedEventIds.push($(this).val());
        });

        if(selectedEventIds.length > 0) {
            if (confirm('Are you sure you want to delete the event?')) {
                currentRowId = encodeURI( selectedEventIds.join('$') );
                window.location.href = $("#deleteUrl").val() + '?event_id=' + currentRowId;
            } else {
              // Do nothing!
            }
        } else {
            $("#delete_event").attr('disabled', true);
        }
    })

    $("#export_event").on('click', function() {
        var selectedEventIds = [];
        $('.checkbox:checked').each(function() {
            selectedEventIds.push($(this).val());
        });

        if(selectedEventIds.length > 0) {
            currentRowId = encodeURI( selectedEventIds.join('$') );
            window.location.href = $("#exportUrl").val() + '?event_id=' + currentRowId;
        } else {
            $("#export_event").attr('disabled', true);
        }
    })

    $("#add_event").on('click', function() {
        $("#event_id").val('');
        $("#banner_image").attr('src', '');
        $("#banner_img_div").hide();

        handlyShowHideDiv('add_event');
    })

    $("#show_events").on('click', function() {
    	handlyShowHideDiv('list_of_events');
    })

    $(document).ready(function() {
        var formValidate = $("#addEventForm").validate({
            rules: {
                name: "required",
                image: {
                    required: function () {
                        return $("#banner_image").attr('src') == '';
                    },
                },
                location: "required",
                start_date: "required",
                end_date: "required",
            }
        });

	    $('#addEventForm').submit(function(e) {
	       if(!formValidate.valid()) return;
           $('input[name=submit]').attr('disabled', true);
	       e.preventDefault();

	       let formData = new FormData(this);

	       $.ajax({
	          	type:'POST',
	          	url: $("#addEventForm").attr('action'),
	           	data: formData,
	           	contentType: false,
	           	processData: false,
	           	success: (response) => {
					if (response) {
						// this.reset();
					}
					location.reload();
	           	},
	           	error: function(response) {
	              	console.log(response);
					location.reload();
	           	}
	       });
	  	});

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
        });

        /*var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'banner', name: 'banner'},
                {data: 'name', name: 'name'},
                {data: 'location', name: 'location'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });*/
    });
</script>



<style>
label.error {
	color: #f00;
}
.container {
	padding: 20px;
}
</style>

{!! $dataTable->scripts() !!}

</html>