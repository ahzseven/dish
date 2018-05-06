<?php
namespace app\admin\validate;

/**
 * 会员验证器
 */
class SceneData extends AdminBase
{

    // 验证规则
    protected $rule =   [

        'dish_name'  => 'require',
//        'price_name' => 'require',
//        'module_id'  => 'require',
//        'tpl_id'     => 'require',
        'type'       => 'require',
        'list_num'   => 'require',
    ];

    // 验证提示
    protected $message  =   [

        'dish_name.require'  => '菜品名称不能为空',
//        'price_name.require' => '菜品价格不能为空',
//        'module_id.require'  => '模块不能为空',
//        'tpl_id.require'     => '模板配置不能为空',
        'type.require'       => '选择菜品类型:文字菜?图文菜?',
        'list_num.require'   => '未选择列数',
    ];

    // 应用场景
    protected $scene = [

        'add'   =>  ['dish_name', 'type', 'list_num'],
        'edit'  =>  ['dish_name', 'type', 'list_num'],
    ];
}
