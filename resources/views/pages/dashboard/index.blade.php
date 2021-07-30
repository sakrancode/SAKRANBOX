@extends('adminlte::page')

@section('content_header')
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')
    {{-- <div class="container-fluid"> --}}
        <!-- COLOR PALETTE -->
        <div class="card card-default color-palette-box">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-folder-open"></i>
                    All Files
                </h3>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
                <p>
                    <button class="btn btn-primary btn-sm btnUploadFile" data-toggle="modal" data-target="#modal-default">
                        <i class="fas fa-file-upload"></i>&nbsp;&nbsp;Upload Files
                    </button>
                    {{-- <button class="btn btn-primary btn-sm btnTambah" data-toggle="modal" data-target="#modal-default">
                        <i class="fas fa-folder"></i>&nbsp;&nbsp;Upload Folder
                    </button> --}}
                    <button class="btn btn-primary btn-sm btnAddFolder" data-toggle="modal" data-target="#modal-default">
                        <i class="fas fa-folder-plus"></i>&nbsp;&nbsp;New Folder
                    </button>
                </p>
                <table id="example" style="width:100%;" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:60%;">Name</th>
                            <th>Modified</th>
                            {{-- <th>Access</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr>
                            <td><i class="far fa-fw fa-folder"></i> PPJB</td>
                            <td align="center">15/08/2019 04:40 pm</td>
                            <td align="center">
                                <a href="" class="btn btn-outline-secondary btn-xs" target="_blank" title="Share">
                                    <i class="fas fa-share-alt-square"></i>
                                </a>
                                <a href="" class="btn btn-outline-secondary btn-xs" target="_blank" title="Copy Link">
                                    <i class="fas fa-link"></i>
                                </a>
                                <div class="btn-group dropleft">
                                    <button type="button" class="btn btn-outline-secondary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="More">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-arrows-alt"></i> Move
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-copy"></i> Copy
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-pencil-alt"></i> Rename
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="far fa-fw fa-folder"></i> AJB</td>
                            <td align="center">20/08/2019 04:80 pm</td>
                            <td align="center">
                                <a href="" class="btn btn-outline-secondary btn-xs" target="_blank" title="Share">
                                    <i class="fas fa-share-alt-square"></i>
                                </a>
                                <a href="" class="btn btn-outline-secondary btn-xs" target="_blank" title="Copy Link">
                                    <i class="fas fa-link"></i>
                                </a>
                                <div class="btn-group dropleft">
                                    <button type="button" class="btn btn-outline-secondary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="More">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-arrows-alt"></i> Move
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-copy"></i> Copy
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-pencil-alt"></i> Rename
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="far fa-fw fa-folder"></i> Akta</td>
                            <td align="center">29/08/2019 05:40 pm</td>
                            <td align="center">
                                <a href="" class="btn btn-outline-secondary btn-xs" target="_blank" title="Share">
                                    <i class="fas fa-share-alt-square"></i>
                                </a>
                                <a href="" class="btn btn-outline-secondary btn-xs" target="_blank" title="Copy Link">
                                    <i class="fas fa-link"></i>
                                </a>
                                <div class="btn-group dropleft">
                                    <button type="button" class="btn btn-outline-secondary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="More">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-arrows-alt"></i> Move
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-copy"></i> Copy
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-pencil-alt"></i> Rename
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    {{-- </div> --}}

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

{{-- @section('footer')
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2018 <a href="https://rlms.com">RLMS</a>.</strong> All rights reserved.
@endsection --}}

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/css/datatables.min.css') }}"/>

    <style>
        /* Handle dropdown-menu position overflow with tabel header */
        .table-responsive,
        .dataTables_scrollBody {
            overflow: visible !important;
        }

        .table th {
            text-align: center;
        }
    	th{color:#fff; background:#777171!important;}
    </style>

    <link rel="stylesheet" href="{{ asset('multiple-prevent/submit.css') }}">

    <link rel="stylesheet" href="{{asset('jstree/dist/themes/default/style.min.css')}}" />
@endsection

@section('js')

    <!-- jstree -->
    <script src="{{asset('jstree/dist/jstree.min.js')}}"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="{{ asset('DataTables/js/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/js/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/js/datatables.min.js') }}"></script>

    <script src="{{ asset('pages/dashboard.js') }}"></script>

    <!-- page script -->
    <script>
        $(function () {
            $("#example").DataTable({
                "scrollX":true,
    		    "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                "ajax":{
                         "url": "{{ url('dashboard/getDataDocument') }}",
                         "dataType": "json",
                         "type": "POST",
                         "data":{ _token: "{{csrf_token()}}"}
                       },
                "columns": [
                   {"data":"name", "className": "text-center,dt-body-left"},
                   {"data":"updated_at", "className": "text-center"},
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
