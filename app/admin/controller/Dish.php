<?php
namespace app\admin\controller;

use think\Db;
/**
 * 菜品控制器
 */
class dish extends AdminBase
{

    /**
     * 菜品列表
     */
    public function dishList()
    {

        $where = $this->logicDish->getWhere($this->param);

        $this->assign('list', $this->logicDish->getDishList($where, 'a.*,m.nickname,c.name as category_name', 'a.create_time desc'));

        return $this->fetch('dish_list');
    }

    /**
     * 菜品添加
     */
    public function dishAdd()
    {

        $this->dishCommon();

        return $this->fetch('dish_edit');
    }

    /**
     * 菜品编辑
     */
    public function dishEdit()
    {

        $this->dishCommon();

        $info = $this->logicDish->getDishInfo(['a.id' => $this->param['id']], 'a.*,m.nickname,c.name as category_name');

        !empty($info) && $info['img_ids_array'] = str2arr($info['img_ids']);

        $this->assign('info', $info);

        return $this->fetch('dish_edit');
    }

    /**
     * 菜品添加与编辑通用方法
     */
    public function dishCommon()
    {

        IS_POST && $this->jump($this->logicDish->dishEdit($this->param));

        $this->assign('dish_category_list', $this->get_category_all());

    }

    /**
     * 菜品分类添加
     * dish_category_edit
     */
    public function dishCategoryAdd()
    {

        IS_POST && $this->jump($this->logicDish->dishCategoryEdit($this->param));


        // 获取所有分类
        $data = $this->get_category_all();

        $this->assign('dish_category_list', $data);
        return $this->fetch('dish_category_edit');
    }

    /**
     * 菜品分类编辑
     */
    public function dishCategoryEdit()
    {

        IS_POST && $this->jump($this->logicDish->dishCategoryEdit($this->param));

        $info = $this->logicDish->getDishCategoryInfo(['id' => $this->param['id']]);

        // 获取所有分类
        $data = $this->get_category_all();

        $this->assign('dish_category_list', $data);

        $this->assign('info', $info);

        return $this->fetch('dish_category_edit');
    }

    /**
     * 菜品分类列表
     */
    public function dishCategoryList()
    {



        $where = empty($this->param['pid']) ? ['pid' => 0] : ['pid' => $this->param['pid']];


        $this->assign('pid', $where['pid']);
//        var_dump($where['pid']);
//        $this->assign('list', $this->logicDish->getDishCategoryList());

        // 获取列表数据
        $data = $this->logicDish->getCategoryList($where, true, 'sort', DB_LIST_ROWS);
        $this->assign('list', $data);

        return $this->fetch('dish_category_list');
    }

    /**
     * 菜品分类删除
     */
    public function dishCategoryDel($id = 0)
    {

        $this->jump($this->logicDish->dishCategoryDel(['id' => $id]));
    }

    /**
     * 数据状态设置
     */
    public function setStatus()
    {
        $this->jump($this->logicAdminBase->setStatus('Dish', $this->param));
    }

    /**
     * 获取所有菜单分类
     */
    public function get_category_all()
    {
        $map['status']=1;

        $res = Db::name('dish_category')->where($map)->order('sort asc')->select();
//        foreach ($res as $key => $value) {
//            $children = Db::name('dish_category')->where("parent_id = $value[id]")->order('sort asc')->select();
//            $res[$key]['children'] = $children;
//        }
        // 1.获取列表树结构
        $res = $this->logicDish->getListTree($res);
        // 2.树结构转换形式
        $res = $this->logicDish->menuToSelect($res);

        return $res;
    }

    /**
     * 排序
     */
    public function setSort()
    {

        $this->jump($this->logicAdminBase->setSort('DishCategory', $this->param));
    }

    /**
     * 分类列表修改状态
     */
    public function set_status()
    {

        $this->jump($this->logicAdminBase->setStatus('DishCategory', $this->param));
    }

    public function get_same_category($category_name = '私家菜')
    {
        // 区分分类名称和菜名
        // 查询$category_name的id

        $where['name'] = trim($category_name);
        $data = Db::name('dish_category')->where($where)->find();
        // 菜名表中查询所有相同分类id

        return $data['id'];
    }

}
