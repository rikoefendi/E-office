@extends('layouts.app')

@section('content')
<section class="content-header">
  <h1>
    Edit User
  </h1>
  <ol class="breadcrumb">
    <li class="">User</li>
    <li class="active">Edit</li>
  </ol>
</section>
<section class="content">
  <div class="row">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="col-md-3">
      <a href="/users" class="btn btn-primary btn-block margin-bottom">Kembali</a>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Filter</h3>

          <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li><a href="/users"><i class="fa fa-inbox"></i> Semua </a></li>
            <li><a href="/users?level=1"><i class="fa fa-envelope-o"></i> Administrator</a></li>
            <li><a href="/users?level=2"><i class="fa fa-file-text-o"></i> Sekretaris</a></li>
            <li><a href="/users?level=3"><i class="fa fa-star-o"></i> Accepted</a></li>
          </ul>
        </div>
        <!-- /.box-body -->
      </div>
    <!-- /.col -->
    </div>
    <div class="col-md-9">
      <div class="box box-primary">
        <div class="box-body no-padding">
          <form class="" action="/user/update" method="post" enctype="multipart/form-data">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Edit {{$user->name}}</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                  @csrf
                    <div class="form-group @error('name') has-error @enderror">
                      <input type="hidden" name="id" value="{{$user->id}}">
                        <input class="form-control" placeholder="Name" name="name" type="text" value="{{$user->name}}">
                        @error('name')
                            <span class="help-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                      <div class="form-group @error('email') has-error @enderror">
                          <input class="form-control" placeholder="Email" name="email" type="email" value="{{$user->email}}">
                          @error('email')
                              <span class="help-block" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                      <div class="form-group @error('level') has-error @enderror">
                          <select class="form-control" name="level" @if($user->level != 1) disabled @endif>
                            <option value="">Level</option>
                            <option value="1" @if($user->level == 1) selected @endif>Administrator</option>
                            <option value="2" @if($user->level == 2) selected @endif>Sekretaris</option>
                            <option value="3" @if($user->level == 3) selected @endif>Accepted</option>
                          </select>
                          @error('level')
                              <span class="help-block" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                      <div class="form-group @error('status') has-error @enderror" >
                          <select class="form-control" name="status"  @if($user->level != 1) disabled @endif>
                            <option value="">Status</option>
                            <option value="1" @if($user->status == 1) selected @endif>Aktif</option>
                            <option value="2" @if($user->status == 2) selected @endif>Suspend</option>
                          </select>
                          @error('status')
                              <span class="help-block" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                      <div class="form-group @error('password') has-error @enderror">
                        <input type="password" name="password" placeholder="Password" class="form-control">
                        @error('password')
                            <span class="help-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="form-control">
                      </div>
                      <div class="form-group @error('avatar') has-error @enderror">
                            <input type="file" name="avatar" value="" onchange="getNameFile(this)" id="inputfile" style="display:none;">
                          <input type="text" value="" id="fileName" class="form-control" name="avatar-text" onclick="getInput()" readonly placeholder="Avatar">
                          <p class="help-block">Max. 1MB dengan format jpeg,jpg,png</p>
                          @error('avatar')
                              <span class="help-block" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="pull-right">
                  <!-- <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> </button> -->
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </div>
                <!-- <button type="reset" class="btn btn-default"><i class="fa fa-times"></i></button> -->
              </div>
              <!-- /.box-footer -->
            </div>
        </form>
        </div>
      </div>
      <!-- /.row -->
    </div>
  </div>
</section>
@endsection

@section('script')
<script type="text/javascript">

var fileName = document.getElementById('fileName');
var inputfile = document.getElementById('inputfile');
function getInput() {
  return inputfile.click();
}
function getNameFile(n){
  var file = n.files[0]
  fileName.value = file.name;
}
</script>
@endsection
