@extends('layouts.app')

@section('content')
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
<!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       Read Mail
     </h1>
     <ol class="breadcrumb">
       <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
       <li >Approval</li>
       <li class="active">Read</li>
     </ol>
   </section>

   <!-- Main content -->
   <section class="content">
     <div class="row">
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
         <!-- <div class="box box-solid">
           <div class="box-header with-border">
             <h3 class="box-title">Labels</h3>

             <div class="box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
               </button>
             </div>
           </div>
           <div class="box-body no-padding">
             <ul class="nav nav-pills nav-stacked">
               <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
               <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
               <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
             </ul>
           </div>
           /.box-body
         </div> -->
         <!-- /.box -->
       </div>
       <!-- /.col -->
       <div class="col-md-9">
         <div class="box box-primary">
           <div class="box-header with-border">
             <h3 class="box-title">Read Mail</h3>

             <div class="box-tools pull-right">
               <!-- <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
               <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a> -->
             </div>
           </div>
           <!-- /.box-header -->
           <div class="box-body no-padding">
             <div class="mailbox-read-info">
               <h3>{{$parser->getHeader('subject')}}</h3>
               <h5>{{$parser->getHeader('from')}}
                 <span class="mailbox-read-time pull-right">{{carbon($parser->getHeader('date'))}}</span></h5>
             </div>
             <!-- /.mailbox-read-info -->
             <div class="mailbox-controls with-border text-center">
               @if(Auth::user()->level == 2 || Auth::user()->level == 1)
                   <a @if($data['status'] == 0 || $data['status'] == 2) href="/approval/{{$data['id']}}/approve" @endif><label for="" class="btn btn-primary">
                     @if($data['status'] == 1)
                     Approved
                     @else
                     Approve
                     @endif
                   </label></a>
                   <a @if($data['status'] == 0 || $data['status'] == 1) href="/approval/{{$data['id']}}/decline" @endif><label for="" class="btn btn-danger">
                     @if($data['status'] == 2)
                     Declined
                     @else
                     Decline
                     @endif
                   </label></a>
               @elseif(Auth::user()->level == 3)
                 @if($data['status'] == 1)
                   <label for="" class="btn btn-primary">Approve</label>
                 @elseif($data['status'] == 2)
                   <label for="" class="btn btn-danger">Declined</label>
                 @else
                 <label for="" class="btn btn-warning">Pending</label>
                 @endif
                 <div class="btn-group">
                   <a href="/compose?reply={{$parser->getAddresses('from')[0]['address']}}&subject={{$parser->getheader('subject')}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Reply">
                     <i class="fa fa-reply"></i></a>
                   <a href="/forward/{{$data['email_id']}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Forward">
                     <i class="fa fa-share"></i></a>
                 </div>
               @endif
             </div>
             <!-- /.mailbox-controls -->
             <div class="mailbox-read-message">
               {!!$parser->getMessageBody('html')!!}
             </div>
             <!-- /.mailbox-read-message -->
           </div>
           <!-- /.box-body -->
           @if ($parser->getAttachments())
               <div class="box-footer">
                   <ul class="mailbox-attachments clearfix">
                       @foreach ($parser->getAttachments() as $key => $attach)
                           <li>
                               <span class="mailbox-attachment-icon"><i class="fa fa-file-o"></i></span>

                               <div class="mailbox-attachment-info">
                                   <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{$attach->getFileName()}}</a>
                                   <span class="mailbox-attachment-size">
                                       @php
                                        echo round(mb_strlen($attach->getContent(), '8bit') / 1024).' kb';
                                        @endphp
                                       <a target="_blank" href="{{url()->current()}}/download/{{$key}}"class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                       <form action="download" method="post">
                                           {{-- <input type="hidden" name="attach" value="{{dd($attach)}}"> --}}
                                       </form>
                                   </span>
                               </div>
                           </li>
                       @endforeach
                   </ul>
               </div>
               <!-- /.box-footer -->
           @endif
           <!-- /.box-footer -->
         </div>
         <!-- /. box -->
       </div>
       <!-- /.col -->
     </div>
     <!-- /.row -->
   </section>
   <!-- /.content -->
@endsection
