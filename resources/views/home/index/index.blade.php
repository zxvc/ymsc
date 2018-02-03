@extends('home.layouts.base')

@section('content')
    <style>
        body{background-color: #011931}
    </style>
    <div id="mydiv">
        <div class="padding-top-150 text-center">
            <img src="{{$base['logo']}}" />
        </div>
        <div class="style-home-index-search padding-top-50">
            <form class="form-signin">
                <input type="text" id="search" name="search" class="style-home-index-form-control col-xs-10 col-md-11" placeholder="再次输入搜索内容" />
                <button type="submit" class="btn btn-default col-xs-2 col-md-1 style-home-index-form-control-button glyphicon glyphicon-search"></button>
            </form>
            <h6 class="line-height-30 index-font padding-bottom-0 margin-bottom-0">
                大家都在搜：新品6折
            </h6>
            <div class="row padding-right-15">
                <div class="col-xs-12 col-lg-3">
                    <p><a class="btn btn-default style-home-index-btn" href="http://xueshu.baidu.com/" target="_blank" role="button">百度学术</a></p>
                </div>
                @foreach($menus as $menu)
                    <div class="col-xs-12 col-lg-3">
                        <p><a class="btn btn-default style-home-index-btn" href="#" role="button">{{$menu['name']}}</a></p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ URL::asset('/js/canvas-particle.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            //初始化画布的size
            var winWidth=$(window).width();
            var winHeight=$(window).height();
            $('#mydiv').css('width',winWidth);
            $('#mydiv').css('min-height',winHeight-80);
            var config = {
                vx: 4,	//小球x轴速度,正为右，负为左
                vy: 4,	//小球y轴速度
                height: 3,	//小球高宽，其实为正方形，所以不宜太大
                width: 3,
                count: 130,		//点个数
                color: "0,193,184", 	//点颜色
                stroke: "0,102,112", 		//线条颜色
                dist: 10000, 	//点吸附距离
                e_dist: 20000, 	//鼠标吸附加速距离
                max_conn: 1 	//点到点最大连接数
            }
            //调用
            CanvasParticle(config);
        });
    </script>
@endsection