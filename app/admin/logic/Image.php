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

namespace app\admin\logic;

/**
 * 菜品缩略图逻辑
 */
class Image extends AdminBase
{
    
    /**
     * 获取菜品缩略图列表
     */
    public function getImageList($where = [], $field = true, $order = '', $paginate = 0)
    {
        $order = 'sort asc';
        return $this->modelImage->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 菜品缩略图信息编辑
     */
    public function imageEdit($data = [])
    {
        
        $validate_result = $this->validateImage->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateImage->getError()];
        }
        
        $url = url('imageList');
        
        $result = $this->modelImage->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '菜品缩略图' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelImage->getError()];
    }

    /**
     * 获取菜品缩略图信息
     */
    public function getImageInfo($where = [], $field = true)
    {
        
        return $this->modelImage->getInfo($where, $field);
    }
    
    /**
     * 菜品缩略图删除
     */
    public function imageDel($where = [])
    {
        
        $result = $this->modelImage->deleteInfo($where);
        
        $result && action_log('删除', '菜品缩略图删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelImage->getError()];
    }
}
