@extends('adminlte::page')

@section('content_header')
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">PPJB</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">PPJB</li>
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
                    Documents PPJB
                </h3>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
                <table id="example" style="width:100%;" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:60%;">Name</th>
                            <th>Modified</th>
                            <th>Access</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="far fa-file-powerpoint"></i> File Powerpoint</td>
                            <td align="center">15/08/2019 04:40 pm</td>
                            <td>Edinburgh</td>
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
                            <td><i class="far fa-file-word"></i> File Word</td>
                            <td align="center">20/08/2019 04:80 pm</td>
                            <td>Tokyo</td>
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
                            <td><i class="far fa-file-excel"></i> File Excel</td>
                            <td align="center">29/08/2019 05:40 pm</td>
                            <td>San Francisco</td>
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
                            <td><i class="far fa-file-pdf"></i> File PDF</td>
                            <td align="center">29/08/2019 05:40 pm</td>
                            <td>San Francisco</td>
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
                            <td><i class="far fa-file-video"></i> File Video</td>
                            <td align="center">29/08/2019 05:40 pm</td>
                            <td>San Francisco</td>
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
                            <td><i class="far fa-file-audio"></i> File Audio</td>
                            <td align="center">29/08/2019 05:40 pm</td>
                            <td>San Francisco</td>
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
                            <td><i class="far fa-file-image"></i> File Image</td>
                            <td align="center">29/08/2019 05:40 pm</td>
                            <td>San Francisco</td>
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
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Modified</th>
                            <th>Access</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    {{-- </div> --}}
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

    <script src="{{ asset('pages/dashboard.js') }}"></script>

    <!-- page script -->
    <script>
      $(function () {

      });
    </script>
@endsection
