<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/29
 * Time: 10:09
 */

namespace app\Http\Controllers\Admin;

use App\Components\AttributeManager;
use App\Components\GoodsManager;
use App\Components\MenuManager;
use App\Components\QNManager;
use App\Models\ChemClassModel;
use App\Models\GoodsChemAttributeModel;
use App\Models\GoodsModel;
use Illuminate\Http\Request;

class ChemController
{
    const MENU_ID = 1;  //一级栏目
    const F_ATTRIBUTE_ID = 1;  //第一搜索属性
    const S_ATTRIBUTE_ID = 2;  //第二搜索属性
    //首页
    public function index(Request $request){
        $data=$request->all();
        $admin = $request->session()->get('admin');
        //获取二级栏目
        $menu_default=self::MENU_ID;
        $menu_lists=MenuManager::getAllMenuListsByMenuId($menu_default);
        //判断是否有menu_id，如果没有默认第一条数据
        if(array_key_exists('menu_id',$data)){
            $menu_id=$data['menu_id'];
            if(empty($menu_id)){
                $menu_id=$menu_default;
            }
        }
        else{
            $menu_id=$menu_default;
        }
        if(array_key_exists('search',$data)){
            $search=$data['search'];
        }
        else{
            $search='';
        }
        //获取化学商品大类列表
        $chem_classes=GoodsManager::getAllChemClassesByMenuId($search,$menu_id);
        //获取栏目信息
        $menu_info=MenuManager::getMenuById($menu_id);
        $param=array(
            'admin'=>$admin,
            'menu_lists'=>$menu_lists,
            'datas'=>$chem_classes,
            'menu_id'=>$menu_id,
            'menu_info'=>$menu_info
        );
        return view('admin.chem.index', $param);
    }
    //删除商品大类
    public function delClass(Request $request)
    {
        $data=$request->all();
        if(array_key_exists('id',$data)){
            $id=$data['id'];
            if (is_numeric($id) !== true) {
                $return['result']=false;
                $return['msg']='合规校验失败，参数类型不正确';
            }
            else{
                $chem_class = ChemClassModel::find($id);
                $return=null;
                $goodses=GoodsManager::getAllChemGoodsByChemClassId($id);
                if($goodses){
                    $return['result']=false;
                    $return['msg']='删除失败，为了保证网站能够正常运行，请先将此商品大类下的商品删除或转移到其他商品大类下';
                }
                else{
                    $result=$chem_class->delete();
                    if($result){
                        $return['result']=true;
                        $return['msg']='删除成功';
                    }
                    else{
                        $return['result']=false;
                        $return['msg']='删除失败';
                    }
                }
            }
        }
        else{
            $return['result']=false;
            $return['msg']='合规校验失败，缺少参数';
        }
        return $return;
    }
    //创建或编辑商品大类
    public function editClass(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $menu_id=self::MENU_ID;
        $menus=MenuManager::getAllMenuListsByMenuId($menu_id);
        if (array_key_exists('id', $data)) {
            $chem_class = GoodsManager::getAllChemClassByChemClassId($data['id']);
        }
        else{
            $chem_class=new ChemClassModel();
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $param=array(
            'admin'=>$admin,
            'data'=>$chem_class,
            'menus'=>$menus,
            'upload_token'=>$upload_token
        );
        return view('admin.chem.editClass', $param);
    }
    //创建或编辑商品大类执行
    public function editDoClass(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $return=null;
        if(empty($data['picture'])){
            $return['result']=false;
            $return['msg']='编辑商品大类失败,请上传图片';
        }
        else{
            if(empty($data['id'])){
                $chem_class=new ChemClassModel();
            }
            else{
                $chem_class = GoodsManager::getAllChemClassByChemClassId($data['id']);
            }
            $chem_class = GoodsManager::setChemClass($chem_class,$data);
            $result=$chem_class->save();
            if($result){
                $return['result']=true;
                $return['msg']='编辑商品大类成功';
            }
            else{
                $return['result']=false;
                $return['msg']='编辑商品大类失败';
            }
        }
        return $return;
    }
    //商品管理首页
    public function select(Request $request){
        $data=$request->all();
        $admin = $request->session()->get('admin');
        if(array_key_exists('chem_class_id', $data)&&array_key_exists('menu_id', $data)){
            if(empty($data['chem_class_id'])||empty($data['menu_id'])){
                $param=array(
                    'msg'=>'合规校验失败，缺少参数'
                );
                return view('admin.index.error500', $param);
            }
            else{
                $menu_id=$data['menu_id'];
                $chem_class_id=$data['chem_class_id'];
                if(array_key_exists('search',$data)){
                    $search=$data['search'];
                }
                else{
                    $search='';
                }
                //获取商品列表
                $chems=GoodsManager::getAllChemGoodsListsByChemClassId($search,$chem_class_id);
                //获取栏目信息
                $menu_info=MenuManager::getMenuById($menu_id);
                //获取商品大类信息
                $class_info=GoodsManager::getAllChemClassByChemClassId($chem_class_id);
                $param=array(
                    'admin'=>$admin,
                    'datas'=>$chems,
                    'menu_id'=>$menu_id,
                    'chem_class_id'=>$chem_class_id,
                    'class_info'=>$class_info,
                    'menu_info'=>$menu_info
                );
                return view('admin.chem.select', $param);
            }
        }
        else{
            $param=array(
                'msg'=>'合规校验失败，缺少参数'
            );
            return view('admin.index.error500', $param);
        }
    }
    //删除
    public function del(Request $request)
    {
        $data=$request->all();
        if(array_key_exists('id',$data)){
            $id=$data['id'];
            if (is_numeric($id) !== true) {
                $return['result']=false;
                $return['msg']='合规校验失败，参数类型不正确';
            }
            else{
                $goods = GoodsModel::find($id);
                $goods_chem_attribute=GoodsManager::getGoodsChemAttributeByGoodsId($id);
                $return=null;
                $result=$goods->delete();
                $result_attribute=$goods_chem_attribute->delete();
                if($result&&$result_attribute){
                    $return['result']=true;
                    $return['msg']='删除成功';
                }
                else{
                    $return['result']=false;
                    $return['msg']='删除失败';
                }
            }
        }
        else{
            $return['result']=false;
            $return['msg']='合规校验失败，缺少参数';
        }
        return $return;
    }
    //批量删除
    public function delMore(Request $request)
    {
        $data=$request->all();
        if(array_key_exists('id_array',$data)){
            $id_array=explode(',',$data['id_array']);
            $goodses = GoodsManager::getGoodsByMoreId($id_array);
            $count=0;
            foreach ($goodses as $goods){
                $result=$goods->delete();
                $goods_id=$goods['id'];
                $goods_chem_attribute=GoodsManager::getGoodsChemAttributeByGoodsId($goods_id);
                $result_attribute=$goods_chem_attribute->delete();
                if($result&&$result_attribute){
                    $count++;
                }
            }
            $return=null;
            if($count==count($goodses)){
                $return['result']=true;
                $return['msg']='删除成功';
            }
            else{
                $return['result']=false;
                $return['msg']='删除失败';
            }
        }
        else{
            $return['result']=false;
            $return['msg']='合规校验失败，缺少参数';
        }
        return $return;
    }
    //创建或编辑商品
    public function edit(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if(array_key_exists('chem_class_id', $data)&&array_key_exists('menu_id', $data)){
            if(empty($data['chem_class_id'])||empty($data['menu_id'])){
                $param=array(
                    'msg'=>'合规校验失败，缺少参数'
                );
                return view('admin.index.error500', $param);
            }
            else {
                $menu_id = $data['menu_id'];
                $chem_class_id = $data['chem_class_id'];
                $chem_class=GoodsManager::getAllChemClassByChemClassId($chem_class_id);
                $brands = AttributeManager::getAttributeByAttributeId(self::F_ATTRIBUTE_ID);
                $purities = AttributeManager::getAttributeByAttributeId(self::S_ATTRIBUTE_ID);
                if (array_key_exists('id', $data)) {
                    $chem = GoodsManager::getGoodsById($data['id']);
                    $chem['attribute']=GoodsManager::getGoodsChemAttributeByGoodsId($data['id']);
                } else {
                    $chem = new GoodsModel();
                }
                $param = array(
                    'admin' => $admin,
                    'data' => $chem,
                    'menu_id' => $menu_id,
                    'chem_class_id' => $chem_class_id,
                    'brands' => $brands,
                    'purities' => $purities,
                    'chem_class' => $chem_class
                );
                return view('admin.chem.edit', $param);
            }
        }
        else{
            $param=array(
                'msg'=>'合规校验失败，缺少参数'
            );
            return view('admin.index.error500', $param);
        }
    }
    //创建或编辑商品
    public function editDo(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $return=null;
        if(array_key_exists('chem_class_id', $data)&&array_key_exists('menu_id', $data)){
            if(empty($data['chem_class_id'])||empty($data['menu_id'])){
                $return['result']=false;
                $return['msg']='合规校验失败，缺少参数';
            }
            else{
                if(empty($data['id'])){
                    $goods=new GoodsModel();
                    $data['number']=self::ProduceCommodityNumber($data['menu_id']);
                }
                else{
                    $goods = GoodsManager::getGoodsById($data['id']);
                }
                $goods = GoodsManager::setGoods($goods,$data);
                $result=$goods->save();
                if($result){
                    //配置属性参数
                    $data_attribute['goods_id']=$goods->id;
                    $data_attribute['spec']=$data['spec'];
                    $data_attribute['delivery']=$data['delivery'];
                    $data_attribute['depot']=$data['depot'];
                    $data_attribute['merchant']=$data['merchant'];
                    $data_attribute['molecular']=$data['molecular'];
                    $data_attribute['accurate']=$data['accurate'];
                    $data_attribute['chem_class_id']=$data['chem_class_id'];
                    //获取商品的属性
                    $goods_attribute=GoodsManager::getGoodsChemAttributeByGoodsId($data_attribute['goods_id']);
                    if(!$goods_attribute){
                        $goods_attribute=new GoodsChemAttributeModel();
                    }
                    $goods_attribute=GoodsManager::setGoodsChemAttribute($goods_attribute,$data_attribute);
                    $result_attribute=$goods_attribute->save();
                    if($result_attribute){
                        $return['result']=true;
                        $return['msg']='编辑商品成功';
                    }
                    else{
                        $return['result']=false;
                        $return['msg']='编辑商品属性失败';
                    }
                }
                else{
                    $return['result']=false;
                    $return['msg']='编辑商品失败';
                }
            }
        }
        else{
            $return['result']=false;
            $return['msg']='合规校验失败，缺少参数';
        }
        return $return;
    }

    //生成商品号
    public function ProduceCommodityNumber($menu_id){
        $menu=MenuManager::getMenuById($menu_id);
        $prefix=$menu['prefix'];
        $number=$prefix.time().rand(100,1000);
        return $number;
    }
}