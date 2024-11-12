@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="left-side-content">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if (session()->has('success'))
        <div class="alert msg alert-success alert-dismissible"> {!! session('success') !!} <a href="#" class="close"
                data-dismiss="alert" aria-label="close">&times;</a></div>
        @endif @if (session()->has('error'))
            <div class="alert msg alert-danger"> {!! session('error') !!} <a href="#" class="close"
                    data-dismiss="alert" aria-label="close">&times;</a></div>
        @endif
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <div class="row">
                                @if(Auth::user()->role==2)
                                <div class="col-md-8 mb-3">

                                    <a href="{{ route('add-members') }}" class="btn btn-success">
                                        <i class="fa fa-plus"></i>&nbsp;{{ $addText }}</a>
                                </div>
                                @endif


                                <table id="manage-users" class="table table-bordered">
                                    <thead>
                                        <tr class="table-row">
                                            <th>id</th>
                                            <th>FIRST NAME</th>
                                            <th>LAST NAME</th>
                                            <th>PHONE</th>
                                            <th>ADDRESS</th>
                                            <th>REASONS</th>
                                            <th>DESCRIPTION</th>
                                            <th>CENTRALE NAME</th>
                                            <th>COMMUNITY NAME</th>
                                            <th>Support option</th>
                                            <th>CREATED BY</th>
                                            <th>UPDATED AT</th>

                                            <th>STATUS</th>

                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="desc"></div>
        <form id="textGenerationForm" method="POST" action="{{ url('/api/v1/generate-text') }}">
            @csrf
            <div>

                <input type="hidden" id="prompt" name="prompt" required>
            </div>
            <button type="submit">Generate Text</button>

        </form>

        <h2 id="title-ai">Suggestion from AI:</h2>
        <p id="generatedText"></p>
        <div id="loadingSpinner"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div>
        <!-- /.content -->

        <!-- Modal -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel2"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form role="form" id="add-support" action="{{ route('manage-support-save') }}" name="add-support"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
            <div class="col-md-12 mb-3">
                <div class="form-group">

                    <input type="hidden" class="form-control" id="member_id" name="member_id">

                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="description">Reasons<span class="text-danger">*</span></label>
                    <textarea  class="form-control" id="reason" name="reason" row="5"></textarea>
                    <small class="text-danger">{{ $errors->first('reason') }}</small>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="last_name">Amount <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="amaunt" name="amount" placeholder="Amount in Rwandan Franc">

                </div>
            </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-dark btn-lg" id="send_btn2"> <i
            class="fa fa-plus"></i>&nbsp; Save</button>
        </div>
    </div>
</form>
      </div>
    </div>
  </div>
        <!-- /.content -->
    @endsection
    @section('style')
        <link rel="stylesheet" type="text/css" href="{{ asset('custom/datatables/css/dataTables.bootstrap4.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('custom/datatables_custom.css') }}">
    @endsection
    @section('script')
        <script type="text/javascript" src="{{ asset('custom/datatables/js/jquery.dataTables.js') }}"></script>
        <script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.bootstrap4.js') }}"></script>
        <script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.rowReorder.js') }}"></script>
        <script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.scroller.js') }}"></script>
        <script type="text/javascript" src="{{ asset('custom/datatables_custom.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                $('#manage-users').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "dom": '<"pull-left"f><"pull-right"l>tip',
                    "pageLength": 25,
                    "searching": true,
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bInfo": true,
                    "aaSorting": [],
                    "order": [
                        [0, "desc"]
                    ],

                    "ajax": "{{ route('getMembersListAjax') }}",
                    "dataSrc": "data",
                    "fnDrawCallback": function() {
                        $('.toggle-class').bootstrapToggle();
                    },

                    "columns": [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'first_name',
                            name: 'first_name'

                        },
                        {
                            data: 'last_name',
                            name: 'last_name'

                        },
                        {
                            data: 'phone',
                            name: 'phone'

                        },
                        {
                            data: 'address',
                            name: 'address'

                        },
                        {
                            data: 'category_name',
                            name: 'category_name'

                        },
                        {
                            data: 'description',
                            name: 'description'

                        },
                        {
                        data: 'center_name',
                        name: 'center_name'

                        },
                        {
                        data: 'community_name',
                        name: 'community_name'

                        },{
                            data: 'support_status',
                            name: 'support_status',
                        },
                        {
                            data: 'created_by',
                            name: 'created_by'

                        },


                        {
                            data: 'updated_at',
                            name: 'updated_at',
                            "render": function(data) {
                                var nData = (data != null) ? moment(data).format('DD/MM/YYYY') : '';
                                return nData;
                            }
                        },

                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            sortable: false
                        },
                    ],
                    'columnDefs': [{
                            responsivePriority: 1,
                            targets: 5
                        },
                        {
                            responsivePriority: 2,
                            targets: 5
                        },
                        {
                            'visible': false,
                            'targets': [0]
                        }
                    ],
                    'order': [
                        [0, 'desc']
                    ]
                });

                $(document).on('click', '.delete-user', function() {
                    var id = $(this).attr('data-id');
                    var del_url = $(this).attr('data-url');
                    swal({
                        title: 'Are you sure?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#E1261C',
                        cancelButtonColor: '#EBEBEB',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        confirmButtonClass: 'btn',
                        cancelButtonClass: 'btn',
                    }).then(function(result) {
                        if (result.value) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "DELETE",
                                dataType: 'json',
                                url: del_url,
                                success: function(data) {
                                    if (data) {
                                        swal({
                                            title: "Success",
                                            text: "Deleted Successfully.",
                                            type: "success",
                                            confirmButtonColor: '#E1261C',
                                        });
                                        $('#manage-users').DataTable().draw();
                                    }
                                }
                            });
                        }
                    });
                });
            });
            //activate or inactive user
            $(document).on('change', '.view-box', function() {
                var id = $(this).attr('data-id');
                var status_url = $(this).attr('data-url');
                var $this = $(this);
                $this.prop('disabled', true);

                if ($(this).is(":checked")) {
                    var status = 1;
                    var statusname = "Approval";
                } else  {
                    var status = 0;
                    var statusname = "Reject";
                }
                swal({
                    title: 'Are you sure want to ' + statusname + '?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#E1261C',
                    cancelButtonColor: '#EBEBEB',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    confirmButtonClass: 'btn',
                    cancelButtonClass: 'btn',
                }).then(function(result) {

                    if (result.value==true) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: status_url,
                            data: {
                                id: id,
                                status: 1
                            },
                            beforeSend: function() {
                                $('.loader1').show();
                            },
                            success: function(data) {
                                $('.loader1').hide();
                                console.log(data)
                                if (data) {
                                    swal({
                                        title: "Success",
                                        text: "Request approved.",
                                        type: "success",
                                        confirmButtonColor: '#E1261C',
                                    });
                                    $('#manage-users').DataTable().draw();
                                }
                            }
                        });
                    } else if(result.dismiss=="cancel") {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: status_url,
                            data: {
                                id: id,
                                status: 2
                            },
                            beforeSend: function() {
                                $('.loader1').show();
                            },
                            success: function(data) {
                                $('.loader1').hide();
                                if (data) {
                                    swal({
                                        title: "Fail",
                                        text: "This personal rejected.",
                                        type: "success",
                                        confirmButtonColor: '#E1261C',
                                    });
                                    $('#manage-users').DataTable().draw();
                                }
                            }
                        });
                        // $("#manage-users").DataTable().draw();
                    }
                });

            });
            $(document).on('click', '.view-category', function() {
                var name = $(this).attr('data-id-description');
                var title = $(this).attr('data-cat');

                $('#desc').html(name);
                $('#prompt').val(name);
                $('#generatedText').text('');

                $("#exampleModalLabel").html(title)
                document.getElementById('title-ai').style.display = 'none';


            });

            $(document).on('click', '.add-support', function() {
                var name = $(this).attr('data-name');
                var member_id = $(this).attr('data-member-id');

console.log(member_id);
                $('#exampleModalLabel2').html(name);
                $("#member_id").val(member_id)


            });

 $(document).ready(function() {


            $('#add-support').validate({
    rules: {
        reason: {
            required: true,
        },
        amount: {
            required: true,
        },
    },
    messages: {
        reason: {
            required: "Please add the reasons for this support",
        },
        amount: {
            required: "Please enter the estimation of cost for this support",
        },
    },
    errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    },
    submitHandler: function(form, e) {
        e.preventDefault();
        console.log('Form submitted');

        var form_data = new FormData();

        $('#add-support input, #add-support textarea').each(function(i, e) {
            var name = $(this).attr('name');
            form_data.append(name, $(this).val());
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('manage-support-save') }}",
            type: "POST",
            dataType: "json",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#send_btn2').html(
                    "<i class='fa fa-spin fa-spinner'></i> Submit");
            },
            success: function(result) {
                if (result.status == 201) {
                    window.location.href = "{{ route('manage-members') }}";
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
        return false;
    }
});
$( "a.detect-tooltip" ).hover(
function() {
console.log('Hover In');
var title = $(this).attr("data-title");
console.log('Title:', title);
$('<div/>', {
text: title,
class: 'box'
}).appendTo(this);
}, function() {
console.log('Hover Out');
$(document).find("div.box").remove();
}
);

        });
document.getElementById('textGenerationForm').addEventListener('submit', function(event) {
event.preventDefault();

// Show loading spinner
document.getElementById('loadingSpinner').style.display = 'inline-block';
//hide title
document.getElementById('title-ai').style.display = 'none';

const prompt = document.getElementById('prompt').value;
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch('{{ url('/api/v1/generate-text') }}', {
method: 'POST',
headers: {
'Content-Type': 'application/json',
'X-CSRF-TOKEN': csrfToken
},
body: JSON.stringify({ prompt: prompt })
})
.then(response => response.json())
.then(data => {
// Hide loading spinner
document.getElementById('loadingSpinner').style.display = 'none';

//show title
document.getElementById('title-ai').style.display = 'inline-block';

document.getElementById('generatedText').innerText = data.generated_text;
})
.catch(error => {
// Hide loading spinner
document.getElementById('loadingSpinner').style.display = 'none';

console.error('Error:', error);
});
});


        </script>
    @endsection
