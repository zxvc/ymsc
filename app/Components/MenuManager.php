<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/26
 * Time: 10:36
 */

namespace App\Components;

use App\Http\Controllers\Home\ChemController;
use App\Http\Controllers\Home\MachiningController;
use App\Http\Controllers\Home\TestingController;
use App\Models\MenuModel;

class MenuManager
{
    /*
     * 获取一级栏目
     *
     * By zm
     *
     * 2018-01-28
     *
     */
    public static function getClassAMenuLists()
    {
        $menus = MenuModel::where('menu_id',0)->orderBy('sort','desc')->get();
        return $menus;
    }

    /*
     * 获取一级栏目（“显示”状态）
     *
     * By zm
     *
     * 2018-01-28
     *
     */
    public static function getClassAMenuListswhichCanShow()
    {
        $menus = MenuModel::where('menu_id',0)->where('status',1)->orderBy('sort','desc')->get();
        return $menus;
    }

    /*
     * 获取一级栏目以及对应的二级栏目（“显示”状态）
     *
     * By zm
     *
     * 2018-01-28
     *
     */
    public static function getMenuListswhichCanShow()
    {
        $menus = MenuModel::where('menu_id',0)->where('status',1)->orderBy('sort','desc')->get();
        foreach ($menus as $menu){
            $menu_id=$menu['id'];
            $menu['menus']=MenuModel::where('menu_id',$menu_id)->where('status',1)->orderBy('sort','desc')->get();
            if($menu_id==1){
                $menu['column']=ChemController::COLUMN;
            }
            else if($menu_id==2){
                $menu['column']=TestingController::COLUMN;
            }
            else if($menu_id==3){
                $menu['column']=MachiningController::COLUMN;
            }
        }
        return $menus;
    }

    /*
     * 获取一级栏目以及对应的二级栏目
     *
     * By zm
     *
     * 2018-01-28
     *
     */
    public static function getMenuLists()
    {
        $menus = MenuModel::where('menu_id',0)->orderBy('sort','desc')->get();
        foreach ($menus as $menu){
            $menu_id=$menu['id'];
            $menu['menus']=MenuModel::where('menu_id',$menu_id)->orderBy('sort','desc')->get();
        }
        return $menus;
    }

    /*
     * 根据id获得栏目的详细信息
     *
     * By zm
     *
     * 2018-01-28
     *
     */
    public static function getMenuById($id)
    {
        $menu = MenuModel::find($id);
        return $menu;
    }

    /*
     * 根据menu_id获取所有栏目（模糊查询）
     *
     * By zm
     *
     * 2018-01-28
     *
     */
    public static function getAllMenuByMenuId($search,$menu_id)
    {
        $menus=self::getMenuById($menu_id);
        $menu_lists=MenuModel::where('menu_id',$menu_id)->where('name','like','%'.$search.'%')->orderBy('sort','desc')->get();
        $menus['menus']=$menu_lists;
        return $menus;
    }

    /*
     * 根据menu_id获取所有栏目
     *
     * By zm
     *
     * 2018-01-28
     *
     */
    public static function getAllMenuListsByMenuId($menu_id)
    {
        $menus=MenuModel::where('menu_id',$menu_id)->orderBy('sort','desc')->get();
        return $menus;
    }

    /*
     * 配置栏目的参数
     *
     * By zm
     *
     * 2018-01-28
     *
     */
    public static function setMenu($menu, $data){
        if (array_key_exists('name', $data)) {
            $menu->name = array_get($data, 'name');
        }
        if (array_key_exists('menu_id', $data)) {
            $menu->menu_id = array_get($data, 'menu_id');
        }
        if (array_key_exists('prefix', $data)) {
            $menu->prefix = array_get($data, 'prefix');
        }
        if (array_key_exists('picture', $data)) {
            $menu->picture = array_get($data, 'picture');
        }
        if (array_key_exists('sort', $data)) {
            $menu->sort = array_get($data, 'sort');
        }
        if (array_key_exists('status', $data)) {
            $menu->status = array_get($data, 'status');
        }
        if (array_key_exists('seo_title', $data)) {
            $menu->seo_title = array_get($data, 'seo_title');
        }
        if (array_key_exists('seo_keywords', $data)) {
            $menu->seo_keywords = array_get($data, 'seo_keywords');
        }
        if (array_key_exists('seo_description', $data)) {
            $menu->seo_description = array_get($data, 'seo_description');
        }
        return $menu;
    }

}