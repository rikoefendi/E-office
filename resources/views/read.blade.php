@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       Read Mail
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
         <a href="compose.html" class="btn btn-primary btn-block margin-bottom">Compose</a>

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
               <li><a href="mailbox.html"><i class="fa fa-inbox"></i> Inbox
                 <span class="label label-primary pull-right">12</span></a></li>
               <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
               <li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
               <li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a>
               </li>
               <li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
             </ul>
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /. box -->
         <div class="box box-solid">
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
           <!-- /.box-body -->
         </div>
         <!-- /.box -->
       </div>
       <!-- /.col -->
       <div class="col-md-9">
         <div class="box box-primary">
           <div class="box-header with-border">
             <h3 class="box-title">Read Mail</h3>

             <div class="box-tools pull-right">
               <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
               <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
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
               <div class="btn-group">
                 <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Delete">
                   <i class="fa fa-trash-o"></i></button>
                 <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Reply">
                   <i class="fa fa-reply"></i></button>
                 <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Forward">
                   <i class="fa fa-share"></i></button>
               </div>
               <!-- /.btn-group -->
               <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Print">
                 <i class="fa fa-print"></i></button>
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
                           {{-- {{dd($attach)}} --}}
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
           <div class="box-footer">
             <div class="pull-right">
               <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
               <button type="button" class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
             </div>
             <button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
             <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
           </div>
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
