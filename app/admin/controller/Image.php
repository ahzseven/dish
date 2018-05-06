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

namespace app\admin\controller;

/**
 * 菜品缩略图控制器
 */
class Image extends AdminBase
{
    
    /**
     * 菜品缩略图列表
     */
    public function imageList()
    {
//        $where = $this->logicDish->getWhere($this->param);
        $this->assign('list', $this->logicImage->getImageList());
        
        return $this->fetch('image_list');
    }
    
    /**
     * 菜品缩略图添加
     */
    public function imageAdd()
    {
        
        IS_POST && $this->jump($this->logicImage->imageEdit($this->param));
        
        return $this->fetch('image_edit');
    }
    
    /**
     * 菜品缩略图编辑
     */
    public function imageEdit()
    {
        
        IS_POST && $this->jump($this->logicImage->imageEdit($this->param));
        
        $info = $this->logicImage->getImageInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('image_edit');
    }
    
    /**
     * 菜品缩略图删除
     */
    public function imageDel($id = 0)
    {
        
        $this->jump($this->logicImage->imageDel(['id' => $id]));
    }


    /**
     * 排序
     */
    public function setSort()
    {

        $this->jump($this->logicAdminBase->setSort('Image', $this->param));
    }

    /**
     * 分类列表修改状态
     */
    public function set_status()
    {

        $this->jump($this->logicAdminBase->setStatus('Image', $this->param));
    }

}
