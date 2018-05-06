<?php
namespace app\admin\validate;

/**
 * 会员验证器
 */
class SceneCategory extends AdminBase
{

    // 验证规则
    protected $rule =   [
        'name'       => 'require',
    ];

    // 验证提示
    protected $message  =   [

        'name.require' => '页面分类标题不能为空',
    ];

    // 应用场景
    protected $scene = [

        'add'  => ['name'],
        'edit' => ['name'],
    ];
}
