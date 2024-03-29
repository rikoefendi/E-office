@extends('layouts.app')

@section('content')
<section class="content-header">
  <h1>
    Dashboard
  </h1>
  <ol class="breadcrumb">
    <li class="active">Informasi</li>
  </ol>
</section>
<section class="content">
  @if(session('status'))
  <div class="alert alert-success">
      {{ @session('status') }}
  </div>
  @endif
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{$email['approved']}}</h3>

            <p>Email Accepted</p>
          </div>
          <a href="/approval" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{$email['declined']}}</h3>

            <p>Email Declined</p>
          </div>
          <a href="/approval" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{$email['pending']}}</h3>

            <p>Email Pending</p>
          </div>
          <a href="/approval" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      @if(Auth::user()->level == 1)
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{$user}}</h3>

            <p>User Created</p>
          </div>
          <a href="/users" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      @endif
    </div>
</section>
@endsection
