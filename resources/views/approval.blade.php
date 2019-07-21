@extends('layouts.app')

@section('content')
<!-- Main content -->
<section class="content">
  <div class="row">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="col-md-3">
      <a href="/compose" class="btn btn-primary btn-block margin-bottom">Compose</a>

      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Folders</h3>

          <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li><a href="/mailbox"><i class="fa fa-inbox"></i> Inbox </a></li>
            <li><a href="/mailbox?q=in:sent"><i class="fa fa-envelope-o"></i> Sent</a></li>
            <li><a href="/mailbox?q=is:starred"><i class="fa fa-star-o"></i> Starred</a></li>
            <li><a href="/approval"><i class="fa fa-star-o"></i> Approved</a></li>
          </ul>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Approval</h3>

          <!-- <div class="box-tools pull-right">
            <div class="has-feedback">
              <input type="text" class="form-control input-sm" placeholder="Search Mail">
              <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
          </div> -->
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <!-- /.pull-right -->
          </div>
          <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
              <tbody>
                  @foreach($data as $data)
                      <tr>
                          @if(Auth::user()->level == 2 || Auth::user()->level == 1)
                              <td><a @if($data['status'] == 0 || $data['status'] == 2) href="/approval/{{$data['id']}}/approve" @endif><label for="" class="btn btn-primary">
                                @if($data['status'] == 1)
                                Approved
                                @else
                                Approve
                                @endif
                              </label></a></td>
                              <td><a @if($data['status'] == 0 || $data['status'] == 1) href="/approval/{{$data['id']}}/decline" @endif><label for="" class="btn btn-danger">
                                @if($data['status'] == 2)
                                Declined
                                @else
                                Decline
                                @endif
                              </label></a></td>
                          @elseif(Auth::user()->level == 3)
                          <td>
                            @if($data['status'] == 1)
                              <label for="" class="btn btn-primary">Approve</label>
                            @elseif($data['status'] == 2)
                              <label for="" class="btn btn-danger">Declined</label>
                            @else
                            <label for="" class="btn btn-warning">Pending</label>
                            @endif
                            </td>
                          @endif
                        <td class="mailbox-name"><a href="/approval/{{$data['id']}}">{{$data['from']}}</a></td>
                        <td class="mailbox-subject">{!!$data['subject']!!}
                        </td>
                        <td class="mailbox-date">{{carbon($data['date'])->diffForHumans()}}</td>
                      </tr>
                @endforeach
              </tbody>
            </table>
            <!-- /.table -->
          </div>
          <!-- /.mail-box-messages -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-padding">
          <div class="mailbox-controls">
            <!-- <a href="{{$data['prevPage']}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
            <a href="{{$data['nextPage']}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a> -->
          </div>
        </div>
      </div>
      <!-- /. box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>

@endsection
