@extends('home.layouts.base')
@section('content')
    <div id="main-body">
        <div class="style-home-nav-station"></div>
        @include('home.layouts.payProgress')
        <div class="container margin-top-20 text-center">
            <div class="border-box-active margin-top-20 margin-bottom-50 padding-20 width-100" style="min-height:300px;">
                <div class="margin-top-30 margin-bottom-50">
                    <img src="{{URL::asset('img/pay_fail.png')}}" class="width-110"  />
                </div>
                <h4>支付失败，<a href="{{ URL::asset('order/'.$trade_no) }}"><span class="text-blue">返回继续支付</span></a></h4>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection