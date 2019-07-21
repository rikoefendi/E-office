@extends('layouts.app')
@section('content')
<section class="content-header">
  <h1>
    Users
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="">Users</li>
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
      <a href="/user/create" class="btn btn-primary btn-block margin-bottom">Tambah User</a>
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
        <div class="box-header with-border">
          <h3 class="box-title">Users</h3>

          <div class="box-tools pull-right">
            <div class="has-feedback">
              <input type="text" class="form-control input-sm" placeholder="Search Mail">
              <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
          </div>
          <!-- /.box-tools -->
        </div>
        <div class="box-body no-padding">
          <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
             <thead>
               <tr>
                 <th>#</th>
                 <th>Nama</th>
                 <th>Avatar</th>
                 <th>Email</th>
                 <th>Level</th>
                 <th>Status</th>
                 <th>Action</th>
               </tr>
             </thead>
             <tbody>
               @if(!$users->total())
               <tr>
                 <td colspan="7">Tidak Dapat Menemukan Data</td>
               </tr>
               @else
               @foreach($users as $i => $user)
               <tr>
                 <td>{{$i + 1}}</td>
                 <td>{{$user->name}}</td>
                 <td>
                   <img src="{{$user->avatar}}" alt="" width="60px">
                 </td>
                 <td>{{$user->email}}</td>
                 @if($user->level == 1)
                 <td>Administrator</td>
                 @elseif($user->level == 2)
                 <td>Sekretaris</td>
                 @elseif($user->level == 3)
                 <td>Accepted</td>
                 @else
                 <td></td>
                 @endif
                 @if($user->status == 1)
                 <td>
                   <label class="label label-success">Aktif</label>
                 </td>
                 @else
                 <td>
                   <label class="label label-warning">Suspend</label>
                 </td>
                 @endif
                 <td>
                   <a href="/user/{{$user->id}}/edit" class="btn btn-primary btn-xs"> <i class="fa fa-pencil"></i></a>
                   <a href="/user/{{$user->id}}/delete" class="btn btn-danger btn-xs"> <i class="fa fa-trash"></i></a>
                 </td>
               </tr>
               @endforeach
               @endif
             </tbody>
           </table>
            <!-- /.table -->
          </div>
          <!-- /.mail-box-messages -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-padding">
          <div class="mailbox-controls text-center">
            {{$users->links()}}
            <!-- /.pull-right -->
          </div>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </div>
</section>
@endsection

@section('style')
<style media="screen">
.table > tbody > tr > td {
     vertical-align: middle;
}
</style>
@endsection
