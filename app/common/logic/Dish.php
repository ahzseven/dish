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
 * 菜品逻辑
 */
class Dish extends LogicBase
{

    public static $menuSelect  = [];

    /**
     * 菜品分类编辑
     */
    public function dishCategoryEdit($data = [])
    {

        $validate_result = $this->validateDishCategory->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateDishCategory->getError()];
        }

        $url = url('dishCategoryList');

        $result = $this->modelDishCategory->setInfo($data);

        $handle_text = empty($data['id']) ? '新增' : '编辑';

        $result && action_log($handle_text, '菜品分类' . $handle_text . '，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelDishCategory->getError()];
    }

    /**
     *
     * 搜索关键字获取菜品列表
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
     * 获取菜品列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = [];

        !empty($data['search_data']) && $where['a.name|a.describe'] = ['like', '%' . $data['search_data'] . '%'];

        return $where;
    }

    /**
     * 菜品信息编辑
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

        $result && action_log($handle_text, '菜品' . $handle_text . '，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '菜品操作成功', $url] : [RESULT_ERROR, $this->modelDish->getError()];
    }

    /**
     * 获取菜品信息
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
    public function getDishCategoryInfo($where = [], $field = true)
    {

        return $this->modelDishCategory->getInfo($where, $field);
    }

    /**
     * 获取菜品分类列表
     */
    public function getDishCategoryList($where = [], $field = true, $order = '', $paginate = 0)
    {


        return $this->modelDishCategory->getList($where, $field, $order, $paginate);


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
     * 菜品分类删除
     */
    public function dishCategoryDel($where = [])
    {

        $result = $this->modelDishCategory->deleteInfo($where);

        $result && action_log('删除', '菜品分类删除，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '菜品分类删除成功'] : [RESULT_ERROR, $this->modelDishCategory->getError()];
    }

    /**
     * 菜品删除
     */
    public function dishDel($where = [])
    {

        $result = $this->modelDish->deleteInfo($where);

        $result && action_log('删除', '菜品删除，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '菜品删除成功'] : [RESULT_ERROR, $this->modelDish->getError()];
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

        return $this->modelDishCategory->getList($where, $field, $order, $paginate);
    }

    public function get_same_category($category_name)
    {
        // 查询$category_name的id
        $where['name'] = $category_name;
        $id = Db::name('dish_category')->where($where)->find();
        // 菜名表中查询所有相同分类id
    }

    // 查询菜名的img_ids
    public function getDishImg($data)
    {
        $this->modelDish->alias('a');

        $where['name'] = $data;
//        $where["cover_id"] = ['gt', 0];

        return $this->modelDish->getInfo($where); // 2
    }


}
