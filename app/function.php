<?php
// 扩展函数文件，系统研发过程中需要的函数建议放在此处，与框架相关函数分离

/**
 * 审核状态
 * @param $var
 */
function check($var){
    switch($var){
        case 0:
            echo "<button>" . '待审核' . "</button>";
            break;
        case 1:
            echo "<button>" . '审核通过' . "</button>";
            break;
        case 2:
            echo "<button>" . '审核未通过' . "</button>";
            break;
        default:
            echo "<button>" . '审核状态异常' . "</button>";
            break;
    }
}

// 材质类型
function check_material($var){
    switch($var){
        case 0:
            echo '未选';
            break;
        case 1:
            echo 'PVC材质';
            break;
        case 2:
            echo '真皮材质';
            break;
        default:
            echo '材质状态异常';
            break;
    }
}

// AB面是否相同
function check_isab($var){
    switch($var){
        case 0:
            echo '未选';
            break;
        case 1:
            echo 'AB面相同';
            break;
        case 2:
            echo 'AB面不同';
            break;
        default:
            echo '类型选择异常';
            break;
    }
}

// 菜品类型
function check_type($var){
    switch($var){
        case 0:
            echo '分类';
            break;
        case 1:
            echo '文字菜';
            break;
        case 2:
            echo '图文菜';
            break;
        default:
            echo '类型异常';
            break;
    }
}