@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Articles</h1>
@stop

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Article list
                            <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;">Add
                                Article</a>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <table id="article-table" class="table table-striped">
                            <thead>
                            <tr>
                                <th width="30">ID</th>
                                <th>Title</th>
                                {{--  <th>Description</th>--}}
                                <th>Main image</th>
                                <th>Date</th>
                                {{-- <th>Url</th>--}}
                                <th></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.form')

    </div>
    @stop

    @section('css')
            <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/datatables/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
          rel="stylesheet">
    <link href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('assets/bootstrap/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    {{--    <link href="{{ asset('assets/bootstrap/css/navbar-fixed-top.css') }}" rel="stylesheet">--}}
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>

    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    {{--<script src="{{ asset('assets/jquery/jquery-1.12.4.min.js') }}"></script>--}}

    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{{ asset('assets/bootstrap/js/ie10-viewport-bug-workaround.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        var table = $('#article-table').DataTable({
            processing: true,
            serverSide: true,
            /*order: [[0, "desc"]],*/
            ajax: "{{ route('api.article') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                /*{data: 'description', name: 'description'},*/
                {data: 'show_photo', name: 'show_photo'},
                {data: 'date', name: 'date'},
                /* {data: 'url', name: 'url'},*/
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Add Article');
        }

        function showForm(id) {
            save_method = 'show';
            $('input[name=_method]').val('GET');
            $('#modal-form-show form')[0].reset();
            $.ajax({
                url: "{{ url('admin/article') }}" + '/' + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    $('#modal-form-show').modal('show');
                    $('#title_modal').text('Show Article');
                    $('#show_id').val(data.id);
                    $('#show_title').val(data.title).attr('disabled','disabled');
                    $('#show_description').val(data.description).attr('disabled','disabled');
                    $('#show_photo').attr('src',"/upload/photo/"+data.photo);
                    $('#show_date').val(data.date).attr('disabled','disabled');
                    $('#show_url').val(data.url).attr('disabled','disabled');
                    $('#btn_submit').hide();
                },
                error: function () {
                    alert("Nothing Data");
                }
            });
        }
        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('admin/article') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Article');
                    $('#id').val(data.id);
                    $('#title').val(data.title);
                    $('#description').val(data.description);
                    $('#date').val(data.date);
                    $('#url').val(data.url);
                },
                error: function () {
                    alert("Nothing Data");
                }
            });
        }
        function deleteData(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url: "{{ url('admin/article') }}" + '/' + id,
                    type: "POST",
                    data: {'_method': 'DELETE', '_token': csrf_token},
                    success: function (data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error: function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }
        $(function () {
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()) {
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('admin/article') }}";
                    else url = "{{ url('admin/article') . '/' }}" + id;
                    $.ajax({
                        url: url,
                        type: "POST",
                        //data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error: function (data) {
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>
    <script type="text/javascript">
        $('#date').datepicker({
            format: 'yyyy-mm-dd'

        });
    </script>
@stop

