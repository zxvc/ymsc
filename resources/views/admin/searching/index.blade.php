@extends('admin.layouts.app')

@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 找货信息管理 <span class="c-gray en">&gt;</span>找货信息列表 <a class="btn btn-success radius btn-refresh r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" onclick="location.replace('{{URL::asset('/admin/searching/index')}}');" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="text-c">
        <form action="{{URL::asset('/admin/searching/index')}}" method="post" class="form-horizontal">
            {{csrf_field()}}
            <input id="search" name="search" type="text" class="input-text" style="width:450px" placeholder="货物\联系人姓名\联系人手机号码">
            <button type="submit" class="btn btn-success">
                <i class="Hui-iconfont">&#xe665;</i> 搜索
            </button>
        </form>
    </div>
    <from action="{{URL::asset('/admin/searching/delMore')}}" method="post"  class="form-horizontal">
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                <a href="javascript:;" onclick="searching_delMore()" class="btn btn-danger radius"><i class="Hui-iconfont"></i> 批量删除</a>
            </span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-hover table-sort" id="table-sort">
                <thead>
                <tr class="text-c">
                    <th width="80">
                        <input type="checkbox" id="checkbox-1">
                    </th>
                    <th width="80">ID</th>
                    <th>货物</th>
                    <th>联系人姓名</th>
                    <th>联系人电话</th>
                    <th width="150">状态</th>
                    <th width="150">留言时间</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        <td>
                            <input type="checkbox" name="id_array" value="{{$data['id']}}" id="checkbox-1">
                        </td>
                        <td>{{$data['id']}}</td>
                        <td class="text-l">{{$data['goods']}}</td>
                        <td class="text-l">{{$data['name']}}</td>
                        <td>{{$data['phonenum']}}</td>
                        <td width="150">
                            @if($data['status'])
                                <span class="label label-success radius">已联系</span>
                            @else
                                <span class="label label-danger radius">待定</span>
                            @endif
                        </td>
                        <td>{{$data['created_at']}}</td>
                        <td class="td-manage">
                            <a title="查看详情" href="javascript:;" onclick="searching_edit('查看详情','{{URL::asset('/admin/searching/edit')}}?id={{$data['id']}}',{{$data['id']}})" class="ml-5" style="text-decoration:none">
                                <i class="Hui-iconfont">&#xe695;</i>
                            </a>
                            <a title="删除" href="javascript:;" onclick="searching_del(this,'{{$data['id']}}')" class="ml-5" style="text-decoration:none">
                                <i class="Hui-iconfont">&#xe6e2;</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </from>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $('.table-sort').dataTable({
        "aaSorting": [[ 2, "desc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "pading":false,
        "searching" : false, //去掉搜索框
        "bLengthChange": false,   //去掉每页显示多少条数据方法
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,1,7]}// 不参与排序的列
        ]
    });

    /*查看加盟信息详情*/
    function searching_edit(title, url, id) {
        // console.log("searching_edit url:" + url);
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }

    /*加盟信息-删除*/
    function searching_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //进行后台删除
            var param = {
                id: id,
                _token: "{{ csrf_token() }}"
            }
            delSearching('{{URL::asset('')}}', param, function (ret) {
                if (ret.result == true) {
                    $(obj).parents("tr").remove();
                    layer.msg(ret.msg, {icon: 1, time: 1000});
                } else {
                    layer.msg(ret.msg, {icon: 2, time: 1000})
                }
            })
        });
    }
    function searching_delMore(){
        var id_array=''
        $("input:checkbox[name='id_array']:checked").each(function() { // 遍历name=test的多选框
            id_array=id_array+$(this).val()+',';  // 每一个被选中项的值
        });
        id_array=id_array.substring(0,id_array.length-1)
        var param = {
            id_array: id_array,
            _token: "{{ csrf_token() }}"
        }
        delMoreSearching('{{URL::asset('')}}', param, function (ret) {
            if (ret.result == true) {
                // $(obj).parents("tr").remove();
                layer.msg(ret.msg, {icon: 1, time: 1000});
                $('.btn-refresh').click();
            } else {
                layer.msg(ret.msg, {icon: 2, time: 1000})
            }
        })
    }
</script>
@endsection