@extends('layouts.default')

@section('content')
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
@stop
