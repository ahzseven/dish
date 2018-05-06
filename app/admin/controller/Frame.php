<?php
namespace app\admin\controller;

use think\Db;
/**
 * 架构控制器
 */
class Frame extends AdminBase
{
    /**
     * 架构分类列表
     */
    public function frameList()
    {

        $where = empty($this->param['pid']) ? ['pid' => 0] : ['pid' => $this->param['pid']];


        $this->assign('pid', $where['pid']);
//        var_dump($where['pid']);
//        $this->assign('list', $this->logicFrame->getFrameCategoryList());

        // 获取列表数据
        $data = $this->logicFrame->getFrameList($where, true, 'sort', DB_LIST_ROWS);
        $this->assign('list', $data);

        return $this->fetch('frame_list');
    }

    /**
     * 架构分类添加
     * frame_category_edit
     */
    public function frameAdd()
    {

        IS_POST && $this->jump($this->logicFrame->frameEdit($this->param));


        // 获取所有分类
        $data = $this->get_category_all();

        $this->assign('frame_list', $data);
        return $this->fetch('frame_edit');
    }

    /**
     * 架构分类编辑
     */
    public function frameEdit()
    {

        IS_POST && $this->jump($this->logicFrame->frameEdit($this->param));

        $info = $this->logicFrame->getFrameInfo(['id' => $this->param['id']]);

        // 获取所有分类
        $data = $this->get_category_all();

        $this->assign('frame_list', $data);

        $this->assign('info', $info);

        return $this->fetch('frame_edit');
    }

    /**
     * 获取所有菜单分类
     */
    public function get_category_all()
    {
        $map['status']=1;

        $res = Db::name('frame')->where($map)->order('sort asc')->select();

        // 1.获取列表树结构
        $res = $this->logicDish->getListTree($res);
        // 2.树结构转换形式
        $res = $this->logicDish->menuToSelect($res);

        return $res;
    }




    /**
     * 架构分类删除
     */
    public function frameDel($id = 0)
    {

        $this->jump($this->logicFrame->frameDel(['id' => $id]));
    }



    /**
     * 排序
     */
    public function setSort()
    {

        $this->jump($this->logicAdminBase->setSort('Frame', $this->param));
    }

    /**
     * 分类列表修改状态
     */
    public function set_status()
    {

        $this->jump($this->logicAdminBase->setStatus('Frame', $this->param));
    }


}
