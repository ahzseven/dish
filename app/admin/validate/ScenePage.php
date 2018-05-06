<?php
namespace app\admin\validate;

/**
 * 会员验证器
 */
class ScenePage extends AdminBase
{

    // 验证规则
    protected $rule =   [

        'name'         => 'require',
        'category_num' => 'require',
    ];

    // 验证提示
    protected $message  =   [

        'name.require'         => '菜单页面名称不能为空',
        'category_num.require' => '分类标题数量未选择',
    ];

    // 应用场景
    protected $scene = [

        'add'   =>  ['name', 'category_num'],
        'edit'  =>  ['name', 'category_num'],
    ];
}
