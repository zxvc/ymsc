@extends('admin.layouts.app')

@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 商品管理 <span class="c-gray en">&gt;</span>商品列表 <a class="btn btn-success radius btn-refresh r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" onclick="location.replace('{{URL::asset('/admin/chem/index')}}');" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="text-c">
        <form action="{{URL::asset('/admin/chem/index')}}" method="post" class="form-horizontal">
            {{csrf_field()}}
            <span class="select-box" style="width:200px;">
              <select class="select" size="1" name="menu_id">
                  @foreach($menu_lists as $menu_list)
                      @if($menu_id==$menu_list['id'])
                          <option value="{{$menu_list['id']}}" selected >{{$menu_list['name']}}</option>
                      @else
                          <option value="{{$menu_list['id']}}" >{{$menu_list['name']}}</option>
                      @endif
                  @endforeach
              </select>
            </span>
            <input id="search" name="search" type="text" class="input-text" style="width:250px" placeholder="{{$menu_info['name']}}商品名称">
            <button type="submit" class="btn btn-success">
                <i class="Hui-iconfont">&#xe665;</i> 搜索
            </button>
        </form>
    </div>
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a class="btn btn-primary radius" onclick="chem_edit('添加商品','{{URL::asset('/admin/chem/edit')}}?menu_id={{$menu_id}}')" href="javascript:;">
                <i class="Hui-iconfont">&#xe600;</i> 添加商品
            </a>
        </span>
        <span class="l ml-10">
            <a href="javascript:;" onclick="chem_delMore()" class="btn btn-danger radius"><i class="Hui-iconfont"></i> 批量删除</a>
        </span>
    </div>
    <div class="mt-10">
        <table class="table table-border table-bordered table-bg table-hover table-sort" id="table-sort">
            <thead>
            <tr class="text-c">
                <th width="80">
                    <input type="checkbox" id="checkbox-1">
                </th>
                <th width="80">ID</th>
                <th>名称</th>
                <th width="150">栏目</th>
                <th width="150">更新时间</th>
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
                    <td class="text-l">{{$data['name']}}</td>
                    <td class="text-l">{{$data['menu']['name']}}</td>
                    <td>{{$data['updated_at']}}</td>
                    <td class="td-manage">
                        <a title="编辑" href="javascript:;" onclick="chem_edit('编辑','{{URL::asset('/admin/chem/edit')}}?id={{$data['id']}}&menu_id={{$data['menu_id']}}',{{$data['id']}})" class="ml-5" style="text-decoration:none">
                            <i class="Hui-iconfont">&#xe6df;</i>
                        </a>
                        <a title="删除" href="javascript:;" onclick="chem_del(this,'{{$data['id']}}')" class="ml-5" style="text-decoration:none">
                            <i class="Hui-iconfont">&#xe6e2;</i>
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
        "aaSorting": [[ 1, "desc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "pading":false,
        "searching" : false, //去掉搜索框
        "bLengthChange": false,   //去掉每页显示多少条数据方法
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,5]}// 不参与排序的列
        ]
    });

    /*查看商品详情*/
    function chem_edit(title, url, id) {
        // console.log("chem_edit url:" + url);
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }

    /*商品-删除*/
    function chem_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //进行后台删除
            var param = {
                id: id,
                _token: "{{ csrf_token() }}"
            }
            delChem('{{URL::asset('')}}', param, function (ret) {
                if (ret.result == true) {
                    $(obj).parents("tr").remove();
                    layer.msg(ret.msg, {icon: 1, time: 1000});
                } else {
                    layer.msg(ret.msg, {icon: 2, time: 2000})
                }
            })
        });
    }

    function chem_delMore(){
        var id_array=''
        $("input:checkbox[name='id_array']:checked").each(function() { // 遍历name=test的多选框
            id_array=id_array+$(this).val()+',';  // 每一个被选中项的值
        });
        id_array=id_array.substring(0,id_array.length-1)
        var param = {
            id_array: id_array,
            _token: "{{ csrf_token() }}"
        }
        if(id_array){
            delMoreChem('{{URL::asset('')}}', param, function (ret) {
                if (ret.result == true) {
                    // $(obj).parents("tr").remove();
                    layer.msg(ret.msg, {icon: 1, time: 1000});
                    $('.btn-refresh').click();
                } else {
                    layer.msg(ret.msg, {icon: 2, time: 2000})
                }
            })
        }
        else{
            layer.msg('请选择要删除的信息', {icon: 2, time: 2000})
        }
    }
</script>
@endsection