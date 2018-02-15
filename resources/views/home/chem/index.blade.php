@extends('home.layouts.base')
@section('seo')
    <title>{{$channel['seo_title']}}</title>
    <meta name="keywords" content="{{$channel['seo_keywords']}}" />
    <meta name="description" content="{{$channel['seo_description']}}" />
@endsection
@section('content')
<div id="main-body">
    <div class="style-home-nav-station"></div>
    @include('home.layouts.search')
    @include('home.layouts.banner')
    <div class="container margin-bottom-20" id="goods_lists">
        @foreach($menus as $menu)
            <div class="margin-top-10 line-height-30">
                <div class="col-xs-6 col-sm-8 padding-left-0 letter-spacing-2">
                    <span class="font-size-18 border-bottom-title">
                        {{$menu['name']}}
                    </span>
                </div>
                <div class="col-xs-6 col-sm-4 text-right">
                    <span class="text-red margin-right-10">
                        热销
                    </span>
                    <span>
                        <a href="">
                            更多
                        </a>
                    </span>
                </div>
            </div>
            <div class="clear"></div>
            <div class="row goods-lists-card margin-bottom-20 margin-top-10 letter-spacing-2">
                @foreach($menu['chem_classes'] as $chem_class)
                    <div class="col-xs-12 col-sm-3 padding-top-10 padding-right-10 padding-left-10">
                        <a href="">
                            <div class="text-center padding-bottom-10 padding-right-10 padding-left-10 border-radius-5 border-shadow">
                                <h3 class="style-ellipsis-1">{{$chem_class['name']}}</h3>
                                <h4 class="style-ellipsis-1">{{$chem_class['english_name']}}</h4>
                                <div class="goods-lists-picture">
                                    <img class="img-circle" src="{{$chem_class['picture']}}" alt="Generic placeholder image">
                                </div>
                                <h4 class="style-ellipsis-1">CAS号：{{$chem_class['cas']}}</h4>
                                <h4 class="style-ellipsis-1">分子式：{{$chem_class['molecule']}}</h4>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('script')

@endsection