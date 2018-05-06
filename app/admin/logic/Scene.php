<?php
namespace app\admin\logic;

/**
 * 菜单逻辑
 */
class Scene extends AdminBase
{

    /**
     * 获取菜单信息
     */
    public function getSceneInfo($where = [], $field = true)
    {

        $info = $this->modelScene->getInfo($where, $field);

        $info['member_name'] = $this->modelMember->getValue(['id' => $info['mid']], 'nickname');

        $info['customer_name'] = $this->modelCustomer->getValue(['id' => $info['uid']], 'name');

        return $info;
    }

    /**
     * 获取当前页面信息
     * @param array $where
     * @param bool $field
     * @return mixed
     */
    public function getScenePageInfo($where = [], $field = true)
    {

        $info = $this->modelScenePage->getInfo($where, $field);

        $info['customer_name'] = $this->modelCustomer->getValue(['id' => $info['uid']], 'name');

        return $info;
    }

    /**
     * 获取菜单列表
     */
    public function getSceneList($where = [], $field = 'm.*, b.name as uname, c.nickname as mname', $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelScene->alias('m');

        $join = [
            [SYS_DB_PREFIX . 'customer b', 'm.uid = b.id', 'LEFT'],
            [SYS_DB_PREFIX . 'member c', 'm.mid = c.id'],
        ];

        $where['m.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];

        return $this->modelScene->getList($where, $field, $order, $paginate, $join);
    }


    /**
     * 获取菜单列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = [];

        !empty($data['search_data']) && $where['m.nickname|m.username|m.email|m.mobile'] = ['like', '%'.$data['search_data'].'%'];

        return $where;
    }

    /**
     * 获取存在继承关系的菜单ids
     */
    public function getInheritSceneIds($id = 0, $data = [])
    {

        $scene_id = $this->modelScene->getValue(['id' => $id, 'is_share_scene' => DATA_NORMAL], 'leader_id');

        if (empty($scene_id)) {

            return $data;
        } else {

            $data[] = $scene_id;

            return $this->getInheritSceneIds($scene_id, $data);
        }
    }

    /**
     * 获取菜单的所有下级菜单
     */
    public function getSubSceneIds($id = 0, $data = [])
    {

        $scene_list = $this->modelScene->getList(['leader_id' => $id], 'id', 'id asc', false);

        foreach ($scene_list as $v)
        {

            if (!empty($v['id'])) {

                $data[] = $v['id'];

                $data = array_unique(array_merge($data, $this->getSubSceneIds($v['id'], $data)));
            }

            continue;
        }

        return $data;
    }

    /**
     * 菜单添加
     */
    public function sceneAdd($data = [])
    {

        $validate_result = $this->validateScene->scene('add')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateScene->getError()];
        }

        $url = url('sceneList');

        $result = $this->modelScene->setInfo($data);

        $result && action_log('新增', '新增菜单，username：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '菜品添加成功', $url] : [RESULT_ERROR, $this->modelScene->getError()];
    }

    /**
     * 菜单编辑
     */
    public function sceneEdit($data = [])
    {

        $validate_result = $this->validateScene->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateScene->getError()];
        }

        $url = url('sceneList');

        $result = $this->modelScene->setInfo($data);

        $result && action_log('编辑', '编辑菜单，id：' . $data['id']);

        return $result ? [RESULT_SUCCESS, '菜单编辑成功', $url] : [RESULT_ERROR, $this->modelScene->getError()];
    }

    /**
     * 设置菜单信息
     */
    public function setSceneValue($where = [], $field = '', $value = '')
    {

        return $this->modelScene->setFieldValue($where, $field, $value);
    }

    /**
     * 菜单删除
     */
    public function sceneDel($where = [])
    {

        $url = url('sceneList');

        $result = $this->modelScene->deleteInfo($where);

        $result && action_log('删除', '删除菜单，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '菜单删除成功', $url] : [RESULT_ERROR, $this->modelScene->getError(), $url];
    }

    /**
     * 菜单页面删除
     */
    public function scenePageDel($data = [])
    {

        $url = url('scenepagelist?id='.$data['scene_id'].'&uid='.$data['uid']);

        $result = $this->modelScenePage->deleteInfo($data);

        $result && action_log('删除', '删除菜单，where：' . http_build_query($data));

        return $result ? [RESULT_SUCCESS, '菜单删除成功', $url] : [RESULT_ERROR, $this->modelScenePage->getError(), $url];
    }

    // 页面菜品删除
    public function sceneDataDel($where = [])
    {

        $url = url('sceneList');

        $result = $this->modelSceneData->deleteInfo($where);

        $result && action_log('删除', '删除菜单，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '菜单删除成功', $url] : [RESULT_ERROR, $this->modelSceneData->getError(), $url];
    }

    /**
     * 获取所有菜单id, nickname(姓名)列表
     * @param array $where
     * @param string $field
     * @param string $order
     * @param int|mixed $paginate
     * @return mixed
     */
    public function get_scene_all($where = [], $field = 'm.id,m.nickname', $order = '', $paginate = DB_LIST_ROWS, $join = [])
    {

        $this->modelScene->alias('m');

        $where['m.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];

        return $this->modelScene->getList($where, $field, $order, $paginate, $join);
    }


    // 获取当前菜单的所有页面列表
    public function getScenePageList($where = [], $field = 'm.*, b.name as scene_name, c.name as cname', $order = '', $paginate = DB_LIST_ROWS)
    {
        $this->modelScenePage->alias('m');
        $sceneid = $where['scene_id'];
        $join = [
            [SYS_DB_PREFIX . 'scene b', 'm.scene_id = b.id', 'LEFT'],
            [SYS_DB_PREFIX . 'customer c', 'm.uid = c.id', 'LEFT'],
        ];

        $where['m.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        $where['scene_id'] = ['eq', $sceneid];

        return $this->modelScenePage->getList($where, $field, $order, $paginate, $join);

    }

    /**
     * 获取菜单页面分类下的所有菜品
     * @param array $where
     * @param string $field
     * @param string $order
     * @param int|mixed $paginate
     * @return mixed
     */
    public function getSceneDataList($where = [], $field = 'm.*, b.title as scene_name, c.name as page_name', $order = '', $paginate = DB_LIST_ROWS)
    {
        $this->modelSceneData->alias('m');
//        $pageid = $where['page_id'];

        $data['m.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
//        $where['page_id'] = ['eq', $pageid];
        $data['m.pid'] = ['eq', $where['pid']];
        $data['m.scene_id'] = ['eq', $where['scene_id']];
        $data['m.page_id'] = ['eq', $where['page_id']];
        $order = ['sort'];
        $join = [
            [SYS_DB_PREFIX . 'scene b', 'm.scene_id = b.id', 'LEFT'],
            [SYS_DB_PREFIX . 'scene_page c', 'm.page_id = c.id', 'LEFT'],
        ];


        $info = $this->modelSceneData->getList($data, $field, $order, $paginate, $join);

        return $info;

    }

    // 查询客户的搜索条件
    public function getWhereWord($data = [])
    {

        $where = [];

        !empty($data['search_data']) && $where['a.name'] = ['like', '%'.$data['search_data'].'%'];

        return $where;
    }

    /**
     * 菜单详情页面添加
     */
    public function scenePageAdd($data = [])
    {

        $validate_result = $this->validateScenePage->scene('add')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateScenePage->getError()];
        }

        $url = url('scenepagelist?id='.$data['scene_id'].'&uid='.$data['uid']);


        $result = $this->modelScenePage->setInfo($data);
        if ($result) {

//            $count = $result['category_num'];
            $count =2;
            for ($i = 1; $i <= $count; $i++) {
                $info['scene_id'] = $result['scene_id'];
                $info['type'] = 1;
                $info['page_id']  = $result['id'];
                $this->modelSceneData->setInfo($info);
            }
        }

        $result && action_log('新增', '新增菜单详情页，username：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '菜单详情页添加成功', $url] : [RESULT_ERROR, $this->modelScenePage->getError()];
    }

    /**
     * 页面编辑
     * @param array $data
     * @return array
     */
    public function scenePageEdit($data = [])
    {
        $validate_result = $this->validateScenePage->scene('edit')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateScenePage->getError()];
        }

        $url = url('scenepagelist?id='.$data['scene_id'].'&uid='.$data['uid']);

        $result = $this->modelScenePage->setInfo($data);

        $result && action_log('编辑', '编辑菜单，id：' . $data['id']);

        return $result ? [RESULT_SUCCESS, '页面编辑成功', $url] : [RESULT_ERROR, $this->modelScenePage->getError()];
    }

    /**
     * 页面新增菜品
     * @param array $data
     * @return array
     */
    public function sceneDataAdd($data = [])
    {
        $validate_result = $this->validateSceneData->scene('add')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateSceneData->getError()];
        }

        $url = url('scenedatalist?page_id='.$data['page_id'].'&scene_id='.$data['scene_id'].'&pid='.$data['pid']);

        $result = $this->modelSceneData->setInfo($data);

        $result && action_log('新增', '页面新增菜品，username：' . $data['dish_name']);

        return $result ? [RESULT_SUCCESS, '页面菜品添加成功', $url] : [RESULT_ERROR, $this->modelSceneData->getError()];
    }

    // AJAX编辑页面数据
    public function sceneDataEdit($data = [])
    {
//        $url = url('sceneDataList');

        $result = $this->modelSceneData->setInfo($data);

        $result && action_log('编辑', '编辑菜单，id：' . $data['id']);

        return $result ? [RESULT_SUCCESS, '菜品编辑成功'] : [RESULT_ERROR, $this->modelSceneData->getError()];
    }

    // 编辑菜品图片  scene_data_list
    public function setDishImg($data = [])
    {
        $where['id']      = $data['dishid'];
        $where['pic_url'] = $data['url'];
        $where['pic_id']  = $data['imgid'];

        $result = $this->modelSceneData->setInfo($where);

        $result && action_log('编辑', '编辑图片，id：' . $where['id']);

        return $result ? [RESULT_SUCCESS, '图片保存成功'] : [RESULT_ERROR, $this->modelSceneData->getError()];
    }

    // 页面下的分类列表
    public function getSceneCategoryList($where = [], $field = 'm.*, b.title as title, c.name as pname', $order = '', $paginate = DB_LIST_ROWS)
    {
        $this->modelSceneData->alias('m');
        $join = [
            [SYS_DB_PREFIX . 'scene b', 'm.scene_id = b.id', 'LEFT'],
            [SYS_DB_PREFIX . 'scene_page c', 'm.page_id = c.id', 'LEFT'],
        ];

        $data['m.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        $data['m.pid'] = ['eq', 0];
        $data['m.scene_id'] = ['eq', $where['scene_id']];
        $data['m.page_id'] = ['eq', $where['page_id']];

        $info = $this->modelSceneData->getList($data, $field, $order, $paginate, $join);
//        dump($info);
        return $info;

    }

    // 新增页面分类
    public function sceneCategoryAdd($data = [])
    {
        $validate_result = $this->validateSceneCategory->scene('add')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateSceneCategory->getError()];
        }
        $url = url('scenecategorylist?page_id='.$data['page_id'].'&scene_id='.$data['scene_id']);

        $result = $this->modelSceneData->setInfo($data);

        $result && action_log('新增', '页面新增分类，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '页面分类添加成功', $url] : [RESULT_ERROR, $this->modelSceneData->getError()];
    }

    // 获取页面分类标题信息
    public function getSceneCategoryInfo($where = [], $field = true)
    {

        $info = $this->modelSceneData->getInfo($where, $field);
//        var_dump($info);

        return $info;

    }

    // 编辑页面分类标题信息
    public function sceneCategoryEdit($data = [])
    {
        $validate_result = $this->validateSceneCategory->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateSceneCategory->getError()];
        }
        $url = url('scenecategorylist?page_id='.$data['page_id'].'&scene_id='.$data['scene_id']);

        $result = $this->modelSceneData->setInfo($data);

        $result && action_log('编辑', '编辑菜单页面分类，id：' . $data['id']);

        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelSceneData->getError()];
    }

    // 页面分类删除
    public function sceneCategoryDel($data = [])
    {
        // 返回页面下的分类列表
        $url1 = url('scenecategorylist?page_id='.$data['page_id'].'&scene_id='.$data['scene_id']);
        // 返回分类下的菜品列表
        if (isset($data['pid'])) {
            $url2 = url('scenedatalist?page_id='.$data['page_id'].'&scene_id='.$data['scene_id'].'&pid='.$data['pid']);
        }

        $url = isset($data['pid']) ? $url2 : $url1;
        $result = $this->modelSceneData->deleteInfo($data);

        $result && action_log('删除', '删除菜单，where：' . http_build_query($data));

        return $result ? [RESULT_SUCCESS, '菜单删除成功', $url] : [RESULT_ERROR, $this->modelScene->getError(), $url];
    }



//    public function scenePageDel($data = [])
//    {
//
//        $url = url('scenecategorylist?page_id='.$data['page_id'].'&scene_id='.$data['scene_id']);
//
//        $result = $this->modelSceneData->deleteInfo($data);
//
//        $result && action_log('删除', '删除菜单，where：' . http_build_query($data));
//
//        return $result ? [RESULT_SUCCESS, '菜单删除成功', $url] : [RESULT_ERROR, $this->modelScene->getError(), $url];
//    }
}
