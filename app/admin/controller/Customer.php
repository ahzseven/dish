<?php
namespace app\admin\controller;

/**
 * 客户控制器
 */
class Customer extends AdminBase
{

    /**
     * 客户列表
     */
    public function customerList()
    {

        $where = $this->logicCustomer->getWhere($this->param);

        $this->assign('list', $this->logicCustomer->getCustomerList($where, 'a.*,m.nickname', 'a.create_time desc'));
//        $data = $this->logicCustomer->getCustomerList($where, 'a.*,m.nickname', 'a.create_time desc');
//        var_dump($data);



        return $this->fetch('customer_list');
    }



    /**
     * 客户添加
     */
    public function customerAdd()
    {

        IS_POST && $this->jump($this->logicCustomer->customerEdit($this->param));

        return $this->fetch('customer_edit');
    }

    /**
     * 客户编辑
     */
    public function customerEdit()
    {

        IS_POST && $this->jump($this->logicCustomer->customerEdit($this->param));

        $info = $this->logicCustomer->get_customer_info(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('customer_edit');
    }


    /**
     * 客户状态设置
     */
    public function setStatus()
    {

        $this->jump($this->logicAdminBase->setStatus('Customer', $this->param));
    }
}
