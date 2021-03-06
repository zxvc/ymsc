<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/25
 * Time: 15:00
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerModel extends Model
{
    use SoftDeletes;
    protected $table = 'banner_info';
    public $timestamps = true;
    protected $dates=['deleted_at'];
}