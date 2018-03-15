@extends('admin.layouts.app')

@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 订单管理 <span class="c-gray en">&gt;</span>订单列表 <a class="btn btn-success radius btn-refresh r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" onclick="location.replace('{{URL::asset('/admin/order/index')}}');" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="text-c">
        <form action="{{URL::asset('/admin/order/index')}}" method="post" class="form-horizontal">
            {{csrf_field()}}
            <input id="search" name="search" type="text" class="input-text" style="width:450px" placeholder="订单号">
            <button type="submit" class="btn btn-success" id="" name="">
                <i class="Hui-iconfont">&#xe665;</i> 搜索
            </button>
        </form>
    </div>
    <div class="mt-20">
        <table class="table table-border table-bordered table-bg table-hover table-sort" id="table-sort">
            <thead>
            <tr class="text-c">
                <th width="80">ID</th>
                <th width="150">订单号</th>
                <th>总价</th>
                <th>数量</th>
                <th>备注</th>
                <th>状态</th>
                <th width="150">下单时间</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data['id']}}</td>
                    <td>{{$data['trade_no']}}</td>
                    <td>￥{{$data['total_fee']/100}}</td>
                    <td>{{$data['count']}}</td>
                    <td class="text-l">{{$data['content']}}</td>
                    <td>
                        @if($data['status']==1)
                            <span class="label label-secondary radius">待支付</span>
                        @elseif($data['status']==2)
                            <span class="label label-primary radius">支付成功</span>
                        @elseif($data['status']==3)
                            <span class="label label-success radius">交易成功</span>
                        @elseif($data['status']==4)
                            <span class="label label-danger radius">退款中</span>
                        @elseif($data['status']==4)
                            <span class="label label-warning radius">退款失败</span>
                        @else
                            <span class="label label-default radius">退款成功</span>
                        @endif
                    </td>
                    <td>{{$data['created_at']}}</td>
                    <td class="td-manage">
                        <a title="查看详情" href="javascript:;" onclick="order_edit('查看详情','{{URL::asset('/admin/order/edit')}}?id={{$data['id']}}',{{$data['id']}})" class="ml-5" style="text-decoration:none">
                            <i class="Hui-iconfont">&#xe695;</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $('.table-sort').dataTable({
        "aaSorting": [[ 0, "desc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "pading":false,
        "searching" : false, //去掉搜索框
        "bLengthChange": false,   //去掉每页显示多少条数据方法
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[4,7]}// 不参与排序的列
        ]
    });

    /*查看订单详情*/
    function order_edit(title, url, id) {
        console.log("order_edit url:" + url);
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }


</script>
@endsection