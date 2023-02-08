@extends('layouts.default')

@section('content')
    @if (Auth::check())
    <div class="row">
      <div class="col-md-8">
        <section class="status_form">
          @include('shared._status_form')
        </section>
        <h4>微博列表</h4>
        <hr>
        @include('shared._feed')
      </div>
      <aside class="col-md-4">
        <section class="user_info">
          @include('shared._user_info', ['user' => Auth::user()])
        </section>
      </aside>
    </div>
    @else
    <div class="bg-light p-3 p-sm-5 rounded">
        <h1>Hello everyone !</h1>
        <p class="lead">
            歡迎光臨 Weibo 9 網站
        </p>
        <p>
            讓我們一起從這裡出發吧 ! GO !
        </p>
        <p>
            <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">歡迎註冊</a>
        </p>
    </div>
    @endif
@stop
