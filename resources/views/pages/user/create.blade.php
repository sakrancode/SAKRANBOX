{!! Form::open(['route' => 'user.store',
                'method' => 'post',
                'name' => 'formCreate',
                'id' => 'formCreate',
                'autocomplete' => 'off',
                'enctype' => 'multipart/form-data',
                'class' => 'form-horizontal form-prevent-multiple-submits', 'target' => '']) !!}

<div class="modal-header">
  <h4 class="modal-title">Tambah Data User</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            {{ Form::hidden('co_id', Auth::user()->co_id, ['id' => 'co_id'])}}
            <div class="form-group">
                  {{ Form::label('role', 'Role :', ['class' => '']) }}
                  {{ Form::select('role', $roles, null, ['id' => 'role', 'class' => 'form-control']) }}
            </div>
            <div id="kolom_site"></div>
            <div class="form-group">
                {{ Form::label('nama', 'Nama :', ['class' => '']) }}
                {{ Form::text('nama', null, ['id' => 'nama',
                                             'class' => 'form-control',
                                             'placeholder' => 'Nama',
                                             ]) }}
            </div>
            <div class="form-group">
                {{ Form::label('username', 'Username :', ['class' => '']) }}
                {{ Form::text('username', null, ['id' => 'username',
                                             'class' => 'form-control',
                                             'placeholder' => 'Username',
                                             ]) }}
            </div>
            <div class="form-group">
                  {{ Form::label('password', 'Password :', ['class' => '']) }}
                  {{ Form::text('password', null, ['id' => 'password',
                                               'class' => 'form-control',
                                               'placeholder' => 'Password',
                                               ]) }}
            </div>
         </div>
         <div class="col-md-2"></div>
    </div>
</div>
<div class="modal-footer justify-content-between">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button type="submit" class="btn btn-primary button-prevent-multiple-submits">
      <i class="spinner fa fa-spinner fa-spin"></i> Simpan Data
  </button>
</div>

{!! Form::close() !!}

<script src="{{ asset('multiple-prevent/submit.js') }}"></script>
<script>
    $(function () {
    });
</script>
