@extends('home.layouts.base')
@section('content')
    <div id="sign-body">
        <div class="style-home-nav-station"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8"></div>
                <div class="col-xs-12 col-md-4">
                    <div class="padding-top-50" id="sign-form">
                        <div class="panel panel-default" style="height:413px;">
                            <div class="panel-body">
                                <ul class="nav nav-tabs">
                                    <li role="presentation" class="active"><a href="{{ URL::asset('signIn') }}?type=0">账号登录</a></li>
                                    {{--<li role="presentation"><a href="{{ URL::asset('signIn') }}?type=1">微信登录</a></li>--}}
                                    <li role="presentation">
                                        <a href="https://open.weixin.qq.com/connect/qrconnect?appid=wxe6deb72d8381e0f2&scope=snsapi_login&redirect_uri=http://umylab.com/api/wechatLogincallback&state=1529379474&login_type=jssdk&self_redirect=default">
                                            微信登录
                                        </a>
                                    </li>
                                </ul>
                                @if($msg)
                                <h5 class="text-red margin-top-20">{{$msg}}</h5>
                                @endif
                                <form method="post" id="form-signIn" name="signIn">
                                    {{ csrf_field() }}
                                    @if($msg)
                                        <p class="position-relative margin-top-20">
                                    @else
                                        <p class="position-relative margin-top-40">
                                    @endif
                                            <input type="text" name="phonenum" id="phonenum" class="form-control" placeholder="请输入手机号\邮箱">
                                        </p>
                                    @if($msg)
                                        <p class="position-relative margin-top-25">
                                    @else
                                        <p class="position-relative margin-top-30">
                                    @endif
                                            <input type="password" name="password" id="password" class="form-control" placeholder="请输入密码6-12字符、数字组成">
                                        </p>
                                    @if($msg)
                                        <p class="position-relative form-group margin-top-25">
                                    @else
                                        <p class="position-relative form-group margin-top-30">
                                    @endif
                                            <input type="text" name="verificationCode" id="verificationCode" class="form-control width-55 float-left" placeholder="请输入验证码">
                                            <img src="{{ URL::asset('code') }}" class="form-control width-40 float-right padding-0 border-0 border-radius-0 cursor-pointer"  id="verifyimage"  onclick="this.src='{{ url('code') }}?r='+Math.random();" alt="验证码" />
                                        </p>
                                    <p class="clear"></p>
                                    <p class="margin-top-30">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">登 录</button>
                                    </p>
                                    <p class="position-relative form-group margin-top-30 text-right">
                                        <a href="{{ URL::asset('reset') }}"><span class="text-blue">忘记密码？</span></a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function () {
            //获取上一页链接
            var before_url=document.referrer
            var domain=document.domain
            if(domain+'/signInBindingFail'!=before_url&&domain+'/reset'!=before_url){
                setCookie('before_url',document.referrer,1); //保存链接到cookie，有效期1天
            }
            //初始化画布的size
            var winWidth=$(window).width();
            var winHeight=$(window).height();
            $('#sign-body').css('width',winWidth);
            $('#sign-body').css('height',winHeight-45);
            var bodyHeight=$('#sign-body').height();
            if(bodyHeight>700&&bodyHeight<=800){
                $('#sign-form').css('padding-top','80px');
            }
            else if(bodyHeight>800&&bodyHeight<=900){
                $('#sign-form').css('padding-top','120px');
            }
            else if(bodyHeight>900){
                $('#sign-form').css('padding-top','180px');
            }
            //编辑网站基本信息
            $("#form-signIn").validate({
                onkeyup: false,
                focusCleanup: false,
                success: "valid",
                submitHandler: function (form) {
                    var phonenum=$('#phonenum').val();
                    var password=$('#password').val();
                    var verificationCode=$('#verificationCode').val();
                    if(!phonenum){
                        layer.msg('请输入手机号或邮箱', {icon: 2, time: 2000});
                        $('#phonenum').focus();
                    }
                    else if(!password){
                        layer.msg('请输入密码', {icon: 2, time: 2000});
                        $('#password').focus();
                    }
                    else if(password.length<6||password.length>12){
                        layer.msg('请输入6-12位密码', {icon: 2, time: 2000});
                        $('#password').focus();
                    }
                    else if(!verificationCode){
                        layer.msg('登录失败，请填写验证码', {icon: 2, time: 2000});
                    }
                    else{
                        $("#password").val(hex_md5(password));
                        $(form).ajaxSubmit({
                            type: 'POST',
                            url: "{{ URL::asset('signIn')}}",
                            success: function (ret) {
                                if (ret.result) {
                                    layer.msg(ret.msg, {icon: 1, time: 2000});
                                    delCookie('before_url')
                                    if(before_url){
                                        window.location.href = document.referrer;//返回上一页并刷新
                                    }
                                    else{
                                        window.location.href = "{{URL::asset('/')}}";//返回首页
                                    }
                                } else {
                                    $("#password").val('');
                                    $("#verifyimage").click();
                                    layer.msg(ret.msg, {icon: 2, time: 3000});
                                }
                            },
                            error: function (XmlHttpRequest, textStatus, errorThrown) {
                                $("#password").val('');
                                $("#verifyimage").click();
                                layer.msg('操作失败', {icon: 2, time: 2000});
                                console.log("XmlHttpRequest:" + JSON.stringify(XmlHttpRequest));
                                console.log("textStatus:" + textStatus);
                                console.log("errorThrown:" + errorThrown);
                            }
                        });
                    }
                }

            });
        });
    </script>
@endsection