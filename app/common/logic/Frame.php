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

namespace app\common\logic;


/**
 * 架构逻辑
 */
class Frame extends LogicBase
{

    public static $menuSelect  = [];

    /**
     * 架构分类编辑
     */
    public function frameEdit($data = [])
    {

        $validate_result = $this->validateFrame->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateFrame->getError()];
        }

        $url = url('frameList');

        $result = $this->modelFrame->setInfo($data);

        $handle_text = empty($data['id']) ? '新增' : '编辑';

        $result && action_log($handle_text, '架构分类' . $handle_text . '，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelFrame->getError()];
    }

    /**
     *
     * 搜索关键字获取架构列表
     */
    public function getDishList($where = [], $field = 'a.*', $order = '')
    {

        $this->modelDish->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'member m', 'a.mid = m.id'],
            [SYS_DB_PREFIX . 'dish_category c', 'a.category_id = c.id'],
        ];

        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];

        return $this->modelDish->getList($where, $field, $order, DB_LIST_ROWS, $join);
    }

    /**
     * 获取架构列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = [];

        !empty($data['search_data']) && $where['a.name|a.describe'] = ['like', '%' . $data['search_data'] . '%'];

        return $where;
    }

    /**
     * 架构信息编辑
     */
    public function dishEdit($data = [])
    {

        $validate_result = $this->validateDish->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateDish->getError()];
        }

        $url = url('dishList');

        empty($data['id']) && $data['mid'] = mid;

//        $data['content'] = html_entity_decode($data['content']);

        $result = $this->modelDish->setInfo($data);

        $handle_text = empty($data['id']) ? '新增' : '编辑';

        $result && action_log($handle_text, '架构' . $handle_text . '，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '架构操作成功', $url] : [RESULT_ERROR, $this->modelDish->getError()];
    }

    /**
     * 获取架构信息
     */
    public function getDishInfo($where = [], $field = 'a.*')
    {

        $this->modelDish->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'member m', 'a.mid = m.id'],
            [SYS_DB_PREFIX . 'dish_category c', 'a.category_id = c.id'],
        ];

        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];

        return $this->modelDish->getInfo($where, $field, $join);
    }

    /**
     * 获取分类信息
     */
    public function getFrameInfo($where = [], $field = true)
    {

        return $this->modelFrame->getInfo($where, $field);
    }

    /**
     * 获取架构分类列表
     */
    public function getFrameList($where = [], $field = true, $order = '', $paginate = 0)
    {


        return $this->modelFrame->getList($where, $field, $order, $paginate);


    }


    /**
     * 1.获取列表树结构
     */
    public function getListTree($list = [])
    {

        if (is_object($list)) {

            $list = $list->toArray();
        }
        return list_to_tree(array_values($list), 'id', 'pid', 'child');
    }


    /**
     * 2.菜单转换|--形式
     */
    public function menuToSelect($menu_list = [], $level = 0, $name = 'name', $child = 'child')
    {

        foreach ($menu_list as $info) {

            $tmp_str = str_repeat("&nbsp;", $level * 4);

            $tmp_str .= "├";

            $info['level'] = $level;

            $info[$name] = empty($level) || empty($info['pid']) ? $info[$name] . "&nbsp;" : $tmp_str . $info[$name] . "&nbsp;";

            if (!array_key_exists($child, $info)) {

                array_push(self::$menuSelect, $info);
            } else {

                $tmp_ary = $info[$child];

                unset($info[$child]);

                array_push(self::$menuSelect, $info);

                $this->menuToSelect($tmp_ary, ++$level, $name, $child);
            }
        }

        return self::$menuSelect;
    }


    /**
     * 架构分类删除
     */
    public function frameDel($where = [])
    {

        $result = $this->modelFrame->deleteInfo($where);

        $result && action_log('删除', '架构分类删除，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '架构分类删除成功'] : [RESULT_ERROR, $this->modelFrame->getError()];
    }


    /**
     * 获取分类列表
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param bool $paginate
     * @return mixed
     */
    public function getCategoryList($where = [], $field = true, $order = '', $paginate = false)
    {

        return $this->modelFrame->getList($where, $field, $order, $paginate);
    }
    


}
