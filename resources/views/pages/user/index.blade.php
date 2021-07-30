@extends('adminlte::page')

@section('title', 'USER')

@section('content_header')
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">User</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">User</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')

    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data User</h3>
          </div>
          <!-- /.card-header -->
            <div class="card-body">
                <p>
                    <button class="btn btn-primary btn-sm btnTambah" data-toggle="modal" data-target="#modal-default">
                        <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Tambah Data
                    </button>
                </p>

                <table id="tableData" style="width:100%;" class="table table-bordered table-hover">
                  <thead>
                      <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>

            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/css/datatables.min.css') }}"/>

    <style>
        .table th {
            text-align: center;
        }
    	th{color:#fff; background:#777171!important;}
    </style>

    <link rel="stylesheet" href="{{ asset('multiple-prevent/submit.css') }}">
@endsection

@section('js')

    <!-- DataTables -->
    <script type="text/javascript" src="{{ asset('DataTables/js/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/js/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/js/datatables.min.js') }}"></script>

    <script src="{{ asset('pages/user.js') }}"></script>

    <!-- page script -->
    <script>
    $(function () {
        $("#tableData").DataTable({
            "scrollX":true,
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('user/getDataUsers') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
               {"data":"name", "className": "text-center,dt-body-left"},
               {"data":"username", "className": "text-center"},
               {"data":"role", "className": "text-center"},
               {"data":"status", "className": "text-center"},
               {"data":"btnOther", "className": "text-center", "orderable": false, "searchable": false}
             ],
             rowCallback: function (row, data) {
                 // console.log(data.klsBaris);
                 switch(data.klsBaris) {
                   case "alert-danger":
                     $(row).addClass('alert-danger');
                     break;
                   case "alert-info":
                     $(row).addClass('alert-info');
                     break;
                   case "alert-secondary":
                     $(row).addClass('alert-secondary');
                     break;
                   case "alert-success":
                     $(row).addClass('alert-success');
                     break;
                   case "alert-warning":
                     $(row).addClass('alert-warning');
                     break;
                   default:
                     // code block
                 }
             },
            // dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            // buttons: [
            //             {
            //               extend: 'excel',
            //               title	: 'NOTABOX : DATA DOCUMENT',
            //               messageTop: 'DATA DOCUMENT',
            //               text: '<i class="fa fa-file-excel-o"></i> Excel',
            //               className: 'excel btn btn-default btn-small',
            //               titleAttr: 'Excel',
            //               exportOptions:
            //               {
            //                   columns: [0,1]
            //               }
            //             },
            //           {
            //               extend : 'pdf',
            //               title	: 'NOTABOX : DATA DOCUMENT',
            //               messageTop : 'DATA DOCUMENT',
            //               text : '<i class="fa fa-file-pdf-o"></i> PDF',
            //               className: 'pdf btn btn-default btn-small',
            //               titleAttr: 'PDF',
            //               exportOptions:
            //               {
            //                   columns: [0,1]
            //               }
            //             },
            //           {
            //               extend	: 'print',
            //               title	: '<span style="font-size:0.6em;font-family:Densia Sans;">NOTABOX : DATA DOCUMENT</span>',
            //               messageTop: '<span style="font-size:1.4em">DATA DOCUMENT</span>',
            //               text: '<i class="fa fa-print"></i> Print',
            //               className: 'print btn btn-default btn-small',
            //               titleAttr: 'Print',
            //               exportOptions:
            //               {
            //                   columns: [0,1]
            //               }
            //             },
            //         ],
        });
    });
    </script>
@endsection
