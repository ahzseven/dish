<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\admin\validate;

/**
 * 会员验证器
 */
class Customer extends AdminBase
{

    // 验证规则
    protected $rule =   [
        'name'        => 'require|chsAlpha|max:8',
        'restaurant'  => 'require',
        'address'     => 'require',
        'mobile'      => 'require|max:11|/^1[3-8]{1}[0-9]{9}$/',
    ];

    // 验证提示
    protected $message  =   [

        'name.require'       => '姓名不能为空',
        'name.chsAlpha'      => '姓名只能是中文或字母',
        'name.max'           => '名称最多不能超过8个字符',
        'restaurant.require' => '餐馆名称不能为空',
        'address.require'    => '餐馆地址不能为空',
        'mobile.require'     => '手机号不能为空',
        'mobile.max'         => '手机号必须为11个数字',
    ];

    // 应用场景
    protected $scene = [

        'add'   =>  ['name','restaurant','mobile', 'address'],
        'edit'  =>  ['name','address','restaurant','mobile'],
    ];
}
