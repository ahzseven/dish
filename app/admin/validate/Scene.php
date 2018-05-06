<?php
namespace app\admin\validate;

/**
 * 会员验证器
 */
class Scene extends AdminBase
{

    // 验证规则
    protected $rule =   [

        'name'       => 'require',
        'num'        => 'require|between:5,5000',
        'pagecount'  => 'between:0,50',
        'title'      => 'require',
    ];

    // 验证提示
    protected $message  =   [

        'name.require'      => '菜单名称不能为空',
        'title.require'     => '店名不能为空',
        'pagecount.require' => '产品页面不能为空',
        'num.require'       => '制作菜单数量不能为空',
        'num.between'       => '制作菜单数量5到50000之间',
        'pagecount.between' => '产品页面数量1到60之间',

    ];

    // 应用场景
    protected $scene = [

        'add'   =>  ['name', 'num', 'title', 'pagecount'],
        'edit'  =>  ['name', 'num', 'title', 'pagecount'],
    ];
}
