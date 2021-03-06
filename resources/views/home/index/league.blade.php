@extends('home.layouts.base')
@section('content')
    <div id="league-body" style="min-height:592px;">
        <div class="style-home-nav-station"></div>
        <div class="container" id="league-content">
            <div class="row">
                <div class="col-xs-12 col-md-8" id="style-home-league-contact-company">
                    <div class="style-home-league-contact">
                        <p><h4>{{$common['base']['name']}}</h4></p>
                        <p><h4>电话：{{$common['base']['phonenum']}}</h4></p>
                        <p><h4>邮箱：{{$common['base']['email']}}</h4></p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="padding-top-50">
                        <div class="panel panel-default" style="height:413px;">
                            <div class="panel-body">
                                <form method="post" id="form-league-edit">
                                    {{ csrf_field() }}
                                    <p class="position-relative margin-top-20">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="请输入姓名">
                                    </p>
                                    <p class="position-relative margin-top-20">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="请输入邮箱">
                                    </p>
                                    <p class="position-relative margin-top-20">
                                        <input type="text" name="phonenum" id="phonenum" class="form-control" placeholder="请输入电话">
                                    </p>
                                    <p class="position-relative margin-top-20">
                                        <textarea name="content" id="content" class="form-control" rows="5" style="resize: none;" placeholder="请输入内容"></textarea>
                                    </p>
                                    <p class="margin-top-20">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">提 交</button>
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
            //初始化画布的size
            var winWidth=$(window).width();
            var winHeight=$(window).height();
            $('#league-body').css('width',winWidth);
            $('#league-body').css('height',winHeight-45);

            var bodyHeight=$('#league-body').height();
            if(bodyHeight>700&&bodyHeight<=800){
                $('#league-content').css('padding-top','40px');
            }
            else if(bodyHeight>800&&bodyHeight<=900){
                $('#league-content').css('padding-top','140px');
            }
            else if(bodyHeight>900){
                $('#league-content').css('padding-top','240px');
            }
            //编辑网站基本信息
            $("#form-league-edit").validate({
                // rules: {
                //     name: {
                //         required: true,
                //     },
                //     phonenum:{
                //         required:true,
                //         maxlength:11,
                //         minlength:11
                //     },
                //     email:{
                //         required:true,
                //         email:true
                //     },
                //     content:{
                //         required:true,
                //     }
                // },
                onkeyup: false,
                focusCleanup: false,
                success: "valid",
                submitHandler: function (form) {
                    var name=$('#name').val();
                    var email=$('#email').val();
                    var phonenum=$('#phonenum').val();
                    var content=$('#content').val();
                    if(!name){
                        layer.msg('请输入姓名', {icon: 2, time: 2000});
                        $('#name').focus();
                    }
                    else if(!isEmail(email)){
                        layer.msg('请输入正确的邮箱', {icon: 2, time: 2000});
                        $('#email').focus();
                    }
                    else if(!isPoneAvailable(phonenum)){
                        layer.msg('请输入正确的电话', {icon: 2, time: 2000});
                        $('#phonenum').focus();
                    }
                    else if(!content){
                        layer.msg('请输入内容', {icon: 2, time: 2000});
                        $('#content').focus();
                    }
                    else{
                        $(form).ajaxSubmit({
                            type: 'POST',
                            url: "{{ URL::asset('league')}}",
                            success: function (ret) {
                                // console.log(JSON.stringify(ret));
                                if (ret.result) {
                                    layer.msg(ret.msg, {icon: 1, time: 3000});
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 3000)
                                } else {
                                    layer.msg(ret.msg, {icon: 2, time: 3000});
                                }
                            },
                            error: function (XmlHttpRequest, textStatus, errorThrown) {
                                layer.msg('保存失败', {icon: 2, time: 3000});
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