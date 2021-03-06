@extends('home.layouts.base')
@section('seo')
    <title>{{$channel['seo_title']?$channel['seo_title']:$common['base']['seo_title']}}</title>
    <meta name="keywords" content="{{$channel['seo_keywords']?$channel['seo_keywords']:$common['base']['seo_keywords']}}" />
    <meta name="description" content="{{$channel['seo_description']?$channel['seo_description']:$common['base']['seo_description']}}" />
@endsection
@section('content')
<div id="main-body">
    <div class="style-home-nav-station"></div>
    <div class="height-80"></div>
    @include('home.layouts.search')
    @include('home.layouts.banner')
    <div class="container margin-bottom-20" id="goods_lists">
        @foreach($menus as $menu)
            @if(count($menu['testing_goodses'])>0)
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
                        <a href="{{URL::asset($column.'/lists/'.$menu['id'])}}">
                            更多
                        </a>
                    </span>
                </div>
            </div>
            <div class="clear"></div>
            <div class="row goods-lists-card margin-bottom-20 margin-top-10 letter-spacing-2">
                @foreach($menu['testing_goodses'] as $testing_goods)
                    <div class="col-xs-12 col-sm-3 padding-top-10 padding-right-10 padding-left-10">
                        <a href="{{URL::asset($column.'/detail/'.$testing_goods['id'])}}">
                            <div class="text-center padding-bottom-10 padding-right-10 padding-left-10 border-box height-400">
                                <h3 class="style-ellipsis-2 font-size-20 line-height-25 height-50">{{$testing_goods['name']}}</h3>
                                <div class="goods-lists-picture">
                                    <img class="img-circle" src="{{$testing_goods['picture']}}" alt="{{$testing_goods['name']}}">
                                </div>
                                <a href="tencent://message/?Menu=yes&uin={{$service['qq']}}&Service=300&sigT=45a1e5847943b64c6ff3990f8a9e644d2b31356cb0b4ac6b24663a3c8dd0f8aa12a595b1714f9d45">
                                    <button type="button" class="btn btn-info margin-top-10 margin-bottom-10">立 即 咨 询</button>
                                </a>
                                @if($testing_goods['goods_attribute']['lab'])
                                    <h4 class="style-ellipsis-1">实验室：{{$testing_goods['goods_attribute']['lab']}}</h4>
                                @endif
                                <h4 class="style-ellipsis-1">{{$attributes[0]['name']}}：{{$testing_goods['f_attribute']['name']}}</h4>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            @endif
        @endforeach
    </div>
</div>
@endsection

@section('script')

@endsection