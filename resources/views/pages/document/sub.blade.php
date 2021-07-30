@extends('adminlte::page')

@section('content_header')
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Folder {{$document->name}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
          {!!$breadcrumb!!}
          {{-- <li class="breadcrumb-item active">{{$breadcrumb}}</li> --}}
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
                    {{$document->name}}
                </h3>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
                <p>
                    @php
                        if ($document->level > 1) {
                            $url = 'document/'.$document->parent_id.'/subFolder';
                        } else {
                            $url = 'home';
                        }
                    @endphp
                    <a href="{{url($url)}}" class="btn btn-primary btn-sm" data-val="{{$document->id}}">
                        <i class="fas fa-level-up-alt"></i></i>&nbsp;&nbsp;Up One Level
                    </a>
                    <button class="btn btn-primary btn-sm btnUploadFile" data-val="{{$document->id}}" data-toggle="modal" data-target="#modal-default">
                        <i class="fas fa-file-upload"></i>&nbsp;&nbsp;Upload Files
                    </button>
                    {{-- <button class="btn btn-primary btn-sm btnTambah" data-toggle="modal" data-target="#modal-default">
                        <i class="fas fa-folder"></i>&nbsp;&nbsp;Upload Folder
                    </button> --}}
                    <button class="btn btn-primary btn-sm btnAddFolder" data-val="{{$document->id}}" data-toggle="modal" data-target="#modal-default">
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
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->

                {{-- <div id="html1">
                    <ul>
                      <li>Root node 1
                        <ul>
                          <li>Child node 1</li>
                          <li><a href="#">Child node 2</a></li>
                        </ul>
                      </li>
                        <li class="jstree-open" id="node_1">Root
                          <ul>
                            <li>
                              <a href="#" class="jstree-clicked">Child</a>
                            </li>
                          </ul>
                        </li>
                    </ul>
                </div> --}}

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

        .vakata-context {
           z-index: 1100
        }

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

    <script src="{{ asset('pages/document.js') }}"></script>

    <!-- page script -->
    <script>
        $(function () {
            // $('#html1').jstree();
            //
            // $('#html1').on("changed.jstree", function (e, data) {
            //   console.log(data.selected);
            // });

            $("#example").DataTable({
                "scrollX":true,
    		    "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                "ajax":{
                         "url": "{{ url('document/getDataSubFolder') }}",
                         "dataType": "json",
                         "type": "POST",
                         "data":{ _token: "{{csrf_token()}}", parent_id: "{{$document->id}}"}
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
            });
        });
    </script>
@endsection
