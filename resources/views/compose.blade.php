@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Compose
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="mailbox.html" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>

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
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
        <form class="" action="/compose" method="post" enctype="multipart/form-data">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @csrf
                <input type="hidden" name="threadId" value="{{$data['threadId']}}">
                    <div class="form-group">
                        <input class="form-control" placeholder="To:" name="to" type="email" value="{{$data['reply_to']}}">
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Subject:" name="subject" value="{{$data['subject']}}">
                    </div>
                    <div class="form-group">
                        <textarea id="compose-textarea" class="form-control" style="height: 300px" name="body">
                          {{$forwardMessage}}
                          {!!$data['body']!!}
                        </textarea>
                    </div>
                    <div class="form-group">
                        <div class="btn btn-default btn-file">
                            <i class="fa fa-paperclip"></i> Attachment
                            <input type="file" name="attachment" value="">
                        </div>
                        <p class="help-block">Max. 32MB</p>
                    </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
            </div>
            <!-- /.box-footer -->
          </div>
      </form>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection


@section('script')
<script>

$(function () {
  //Add text editor
  let a = $('#compose-textarea').summernote();

})

</script>

@endsection
