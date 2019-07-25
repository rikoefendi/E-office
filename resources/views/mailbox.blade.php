@extends('layouts.app')

@section('content')
<section class="content-header">
  <h1>
    Read Mail
  </h1>
  <ol class="breadcrumb">
    <li class="">Mailbox</li>
  </ol>
</section>
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
          <h3 class="box-title">{{$data['title']}}</h3>

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
                  @foreach($data['messages'] as $dat)
                      <tr>
                        <td class=""><a href="/mailbox/{{$dat['id']}}/to-approval" title="Sampaikan ke atasan"><i class="fa fa-share text-white"></i></a></td>
                        <td class="mailbox-name"><a href="/read/{{$dat['id']}}">{{trim(explode(" ",$dat['from'])[0], '"')}}</a></td>
                        <td class="mailbox-subject">{!!substr($dat['subject'].' - '.$dat['snippet'], 0, 60)!!}...
                        </td>
                        <td class="mailbox-date">{{carbon($dat['date'])->diffForHumans()}}</td>
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
            <a href="{{$data['prevPage']}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
            <a href="{{$data['nextPage']}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
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
