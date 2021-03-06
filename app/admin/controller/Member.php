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

use think\Db;
/**
 * 会员控制器
 */
class Member extends AdminBase
{

    /**
     * 会员授权
     */
    public function memberAuth()
    {
        
        IS_POST && $this->jump($this->logicMember->addToGroup($this->param));
        
        // 所有的权限组
        $group_list = $this->logicAuthGroup->getAuthGroupList(['member_id' => MEMBER_ID]);
        
        // 会员当前权限组
        $member_group_list = $this->logicAuthGroupAccess->getMemberGroupInfo($this->param['id']);

        // 选择权限组
        $list = $this->logicAuthGroup->selectAuthGroupList($group_list, $member_group_list);
        
        $this->assign('list', $list);
        
        $this->assign('id', $this->param['id']);
        
        return $this->fetch('member_auth');
    }
    
    /**
     * 会员列表
     */
    public function memberList()
    {
        
        $where = $this->logicMember->getWhere($this->param);

        $this->assign('list', $this->logicMember->getMemberList($where));
        
        return $this->fetch('member_list');
    }
    
    /**
     * 会员导出
     */
    public function exportMemberList()
    {
        
        $where = $this->logicMember->getWhere($this->param);
        
        $this->logicMember->exportMemberList($where);
    }
    
    /**
     * 会员添加
     */
    public function memberAdd()
    {
        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'memberlist') {
                return $this->getAuditors();
            }
        }
        IS_POST && $this->jump($this->logicMember->memberAdd($this->param));

        // 获取所有组织架构列表
        $this->assign('frame_list', $this->get_category_all());

        return $this->fetch('member_add');
    }
    
    /**
     * 会员编辑
     */
    public function memberEdit()
    {
        if (IS_AJAX) {
            if (isset($_POST['action']) && $_POST['action'] == 'memberlist') {
                return $this->getAuditors();
            }
        }
        IS_POST && $this->jump($this->logicMember->memberEdit($this->param));
        
        $info = $this->logicMember->getMemberInfo(['id' => $this->param['id']]);

        // 获取所有组织架构列表
        $this->assign('frame_list', $this->get_category_all());
        
        $this->assign('info', $info);
        
        return $this->fetch('member_edit');
    }
    
    /**
     * 会员删除 LH
     */
    public function memberDel($id = 0)
    {
        
        return $this->jump($this->logicMember->memberDel(['id' => $id]));
    }


    /**
     * 获取所有组织架构分类列表
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
     * AJAX请求相关会员id, nickname数据列表
     */
    private function getAuditors()
    {
        $where = $this->logicMember->getWhere($this->param);

        $list = $this->logicMember->getMemberList($where, 'm.id, m.nickname');

        return $list;
    }




}
