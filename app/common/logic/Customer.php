<?php
namespace app\common\logic;

/**
 * 客户管理逻辑
 */
class Customer extends LogicBase
{
    /**
     * 获取客户列表
     * SYS_DB_PREFIX	数据表前缀
     * DATA_STATUS_NAME	数据状态KEY标识
     * DATA_DELETE	    数据状态删除
     * DB_LIST_ROWS	    数据列表默认分页
     */
    public function getCustomerList($where = [], $field = 'a.*', $order = '')
    {

        $this->modelCustomer->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'member m', 'a.mid = m.id'],
//            [SYS_DB_PREFIX . 'customer c', 'a.id = c.id'],
        ];

        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];

        return $this->modelCustomer->getList($where, $field, $order, DB_LIST_ROWS, $join);
    }


    /**
     * 获取关键字,返回搜索条件
     */
    public function getWhere($data = [])
    {
        $where = [];

        !empty($data['search_data']) && $where['a.name|a.address|a.restaurant|a.mobile'] = ['like', '%'.$data['search_data'].'%'];

        return $where;
    }

    /**
     * 客户信息编辑
     */
    public function customerEdit($data = [])
    {

        $validate_result = $this->validateCustomer->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateCustomer->getError()];
        }

        $url = url('customerList');

        empty($data['id']) && $data['mid'] = MEMBER_ID;

        $result = $this->modelCustomer->setInfo($data);

        $handle_text = empty($data['id']) ? '新增' : '编辑';

        $result && action_log($handle_text, '客户' . $handle_text . '，name：' . $data['name']);

        return $result ? [RESULT_SUCCESS, '客户操作成功', $url] : [RESULT_ERROR, $this->modelCustomer->getError()];
    }

    /**
     * 获取客户信息
     */
    public function getCustomerInfo($where = [], $field = 'a.*')
    {

        $this->modelCustomer->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'member m', 'a.mid = m.id'],
            [SYS_DB_PREFIX . 'customer_category c', 'a.category_id = c.id'],
        ];

        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];

        return $this->modelCustomer->getInfo($where, $field, $join);
    }

    /**
     * 获取单条客户数据
     */
    public function get_customer_info($where = [], $field = true)
    {

        return $this->modelCustomer->getInfo($where, $field);
    }



    /**
     * 客户删除
     */
    public function customerDel($where = [])
    {

        $result = $this->modelCustomer->deleteInfo($where);

        $result && action_log('删除', '客户删除，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '客户删除成功'] : [RESULT_ERROR, $this->modelCustomer->getError()];
    }
}
