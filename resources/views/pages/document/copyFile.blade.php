{!! Form::model($document, [
                          'route' => ['document.updateCopyFile', $document->id],
                          'method' => 'PUT',
                          'name' => 'formCopyFile',
                          'id' => 'formCopyFile',
                          'enctype' => 'multipart/form-data',
                          'class' => 'form-horizontal form-prevent-multiple-submits', 'target' => '']) !!}

<div class="modal-header">
  <h4 class="modal-title">Copy Data - [{{$document->name}}]</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            {{-- {{ Form::hidden('document_id', $document->id, ['id' => 'document_id'])}} --}}
            {{ Form::hidden('co_id', Auth::user()->co_id, ['id' => 'co_id'])}}
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Pilih Folder Tujuan</h3>
                  </div>
                  <!-- ./card-header -->
                  <div class="card-body p-0">
                      <div id="tree"></div>
                      <div>&nbsp;</div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
            <!-- /.row -->
        </div>
         <div class="col-md-8"></div>
    </div>
</div>
<div class="modal-footer justify-content-between">
    <div id="targetFolder"></div>
  {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}

  <button type="submit" class="btn btn-primary button-prevent-multiple-submits">
      <i class="spinner fa fa-spinner fa-spin"></i> Copy Data
  </button>
</div>

{!! Form::close() !!}

{{-- https://github.com/twbs/bootstrap/issues/27352 --}}
<script src="{{ asset('multiple-prevent/submit.js') }}"></script>

<script>

    $(function () {

    });
</script>
