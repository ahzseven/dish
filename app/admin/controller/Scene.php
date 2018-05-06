<?php
namespace app\admin\controller;
use think\Db;
/**
 * 菜单控制器
 */
class Scene extends AdminBase
{
    /**
     * 菜单列表
     */
    public function sceneList()
    {

        $where = $this->logicScene->getWhere($this->param);

        $this->assign('list', $this->logicScene->getSceneList($where));

        return $this->fetch('scene_list');
    }

    /**
     * 菜单导出
     */
    public function exportSceneList()
    {

        $where = $this->logicScene->getWhere($this->param);

        $this->logicScene->exportSceneList($where);
    }

    /**
     * 菜单添加
     * 异步请求默认请求参数名 action
     */
    public function sceneAdd()
    {
        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'memberlist') {
                return $this->getMembers();
            }
        }

        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'customerlist') {
                return $this->getCustomers();
            }
        }

        IS_POST && $this->jump($this->logicScene->sceneAdd($this->param));

        return $this->fetch('scene_add');
    }

    /**
     * 菜单编辑
     */
    public function sceneEdit()
    {
        // 请求会员信息
        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'memberlist') {
                return $this->getMembers();
            }
        }
        // 请求客户信息
        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'customerlist') {
                return $this->getCustomers();
            }
        }

        IS_POST && $this->jump($this->logicScene->sceneEdit($this->param));

        $info = $this->logicScene->getSceneInfo(['id' => $this->param['id']]);


        $this->assign('info', $info);

        return $this->fetch('scene_edit');
    }

    /**
     * 菜单删除 LH
     */
    public function sceneDel($id = 0)
    {

        return $this->jump($this->logicScene->sceneDel(['id' => $id]));
    }

    // 页面删除
    public function scenePageDel()
    {
        return $this->jump($this->logicScene->scenePageDel($this->param));
    }




    /**
     * AJAX请求会员id, nickname数据列表
     */
    private function getMembers()
    {
        $where = $this->logicScene->getWhere($this->param);

        $list = $this->logicMember->getMemberList($where, 'm.id, m.nickname');

        return $list;
    }

    /**
     * AJAX请求客户id, name数据列表
     */
    private function getCustomers()
    {
        $where = $this->logicScene->getWhereWord($this->param);

        $list = $this->logicCustomer->getCustomerList($where, 'a.id, a.name');

        return $list;
    }

    /**
     * 新增菜单详情页->页面 Page
     * @return mixed
     */
    public function scenePageAdd()
    {
        if (isset($this->param['scene_id'])) {
            $scene_id = $this->param['scene_id'];
            $uid      = $this->param['uid'];

            $this->assign('scene_id', $scene_id);
            $this->assign('uid', $uid);
//            echo $this->param['scene_id'];

            IS_POST && $this->jump($this->logicScene->scenePageAdd($this->param));

            return $this->fetch('scene_page_add');
        }
    }

    /**
     * 菜单详情页编辑 Page
     * @return mixed
     */
    public function scenePageEdit()
    {

        $id = input('param.id');
        $this->assign('id', $id);
        $info = $this->logicScene->getScenePageInfo(['id' => $id]);
        $this->assign('info', $info);

        IS_POST && $this->jump($this->logicScene->scenePageEdit($this->param));

        return $this->fetch('scene_page_edit');

    }



    /**
     * 获取当前菜单的所有页面列表 Page
     * @return mixed
     */
    public function scenePageList()
    {

        $scene_id = $this->param['id'];
        $uid      = $this->param['uid'];
        $this->assign('scene_id', $scene_id);
        $this->assign('uid', $uid);

        $list = $this->logicScene->getScenePageList(['scene_id' => $scene_id]);
        $this->assign('list', $list);

        return $this->fetch('scene_page_list');

    }

    /**
     * 获取页面分类下的所有菜品列表 Datalist
     * @return mixed
     */
    public function sceneDataList()
    {
        $data['scene_id'] = $this->param['scene_id'];
        $data['page_id']  = $this->param['page_id'];
        $data['pid']      = $this->param['pid'];
        $this->assign('data', $data);

        $list = $this->logicScene->getSceneDataList($data);
//         var_dump($list);
        $this->assign('list', $list);

        return $this->fetch('scene_data_list');

    }

    /**
     * 菜单页面分类添加菜品
     * @return mixed
     */
    public function sceneDataAdd()
    {
        // ajax保存菜品
        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'savedishdata') {
                return $this->setSceneData($_POST);
            }
        }
        // ajax获取图片
        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'getimgs') {
                return $this->getimgs($_POST);
            }
        }
        // ajax保存图片
        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'setSceneDataImg') {
                return $this->setSceneDataImg($_POST);
            }
        }


        $data['scene_id'] = $this->param['scene_id'];
        $data['page_id']  = $this->param['page_id'];
        $data['pid']      = $this->param['pid'];
        $this->assign('data', $data);

        IS_POST && $this->jump($this->logicScene->sceneDataAdd($this->param));

        return $this->fetch('scene_data_add');

    }

    /**
     * 处理ajax数据
     * @param $data
     * @return mixed
     */
    private function setSceneData($data)
    {
        $res[$data['name']] = $data['text'];
        $res['id'] = $data['id'];
        $result = $this->logicScene->sceneDataEdit($res);

        return $result;
    }

    /**
     * ajax获取图片
     * @return array
     */
    private function getimgs()
    {
        $data = $this->param;

        // 获取多个图片
        $res = $this->logicDish->getDishImg($data['dish_name']);
        $ids = explode(',',$res['img_ids']);
//        var_dump($ids);
        // 组装id和url
        $list = [];
        foreach($ids as $key => $value) {
            $list[$value] = get_picture_url($value);
        }

        $datas['id'] = $data['id'];
        $datas['img'] = $list;
        return $datas;
    }

    // scene_data_list编辑菜品图片
    private function setSceneDataImg()
    {
//        $data = $this->param;
        return $this->logicScene->setDishImg($this->param);
    }


    // 修改scene状态
    public function setStatus()
    {

        $this->jump($this->logicAdminBase->setStatus('Scene', $this->param));
    }
    // 修改scenePage状态
    public function setPageStatus()
    {

        $this->jump($this->logicAdminBase->setStatus('ScenePage', $this->param));
    }
    // 修改sceneData状态
    public function setDataStatus()
    {

        $this->jump($this->logicAdminBase->setStatus('SceneData', $this->param));
    }

    // 页面分类列表
    public function sceneCategoryList()
    {
        // 页面id  page_id  scene_id  pid=0
        $ids['scene_id'] = $this->param['scene_id'];
        $ids['page_id'] = $this->param['page_id'];
        $this->assign('ids', $ids);
        $list = $this->logicScene->getSceneCategoryList($this->param);
        $this->assign('list', $list);

        return $this->fetch('scene_category_list');
    }
    // 页面分类添加
    public function sceneCategoryAdd()
    {
        $ids['scene_id'] = $this->param['scene_id'];
        $ids['page_id']  = $this->param['page_id'];
        $this->assign('ids', $ids);

        IS_POST && $this->jump($this->logicScene->sceneCategoryAdd($this->param));

        return $this->fetch('scene_category_add');
    }
    // 页面分类编辑
    public function sceneCategoryEdit()
    {

        $ids['id']       = $this->param['id'];
        $ids['scene_id'] = $this->param['scene_id'];
        $ids['page_id']  = $this->param['page_id'];
        $this->assign('ids', $ids);
        $info = $this->logicScene->getSceneCategoryInfo(['id' => $ids['id']]);
        $this->assign('info', $info);

        IS_POST && $this->jump($this->logicScene->sceneCategoryEdit($this->param));

        return $this->fetch('scene_Category_edit');

    }

    // 页面菜品删除
    public function sceneDataDel()
    {
        return $this->jump($this->logicScene->sceneCategoryDel($this->param));
    }


}
