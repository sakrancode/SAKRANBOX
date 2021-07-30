{!! Form::open(['route' => 'document.storeFolder',
                'method' => 'post',
                'name' => 'formCreateFolder',
                'id' => 'formCreateFolder',
                'enctype' => 'multipart/form-data',
                'class' => 'form-horizontal form-prevent-multiple-submits', 'target' => '']) !!}

<div class="modal-header">
  <h4 class="modal-title">Tambah Data Folder {{((!is_null($parent))?'- '.$parent->name:'')}}</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            @if (!is_null($parent))
                {{ Form::hidden('parent_id', $parent->id, ['id' => 'parent_id'])}}
            @endif
            {{ Form::hidden('co_id', Auth::user()->co_id, ['id' => 'co_id'])}}
            <div class="form-group">
                {{ Form::label('nama_folder', 'Judul :', ['class' => '']) }}
                {{ Form::text('nama_folder', null, ['id' => 'nama_folder',
                                             'class' => 'form-control',
                                             'placeholder' => 'Judul Folder',
                                             ]) }}
            </div>
            <div class="form-group">
                {{ Form::label('keterangan', 'Keterangan :', ['class' => '']) }}
                {{ Form::textarea('keterangan', null, ['id' => 'keterangan', 'rows' => '3',
                                             'class' => 'form-control', 'style' => "resize: none;",
                                             'placeholder' => 'Keterangan terkait folder',
                                             ]) }}
            </div>
        </div>
         <div class="col-md-2"></div>
    </div>
</div>
<div class="modal-footer justify-content-between">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
  <button type="submit" class="btn btn-primary button-prevent-multiple-submits">
      <i class="spinner fa fa-spinner fa-spin"></i> Simpan Data
  </button>
  {{-- {{ Form::submit('Simpan Data', [ 'name' => 'simpan',
                              'id' => 'simpan',
                              'class' => 'btn btn-primary', 'data-id' => '2']) }} --}}
</div>

{!! Form::close() !!}

{{-- https://github.com/twbs/bootstrap/issues/27352 --}}
<script src="{{ asset('multiple-prevent/submit.js') }}"></script>
<script src="{{ asset('bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script>
    $(function () {
        bsCustomFileInput.init()
    });
</script>
