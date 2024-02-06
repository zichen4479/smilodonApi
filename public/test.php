<?php
/**
 * Created by webplus360
 * Author: webplus360
 * DATE: $(DATE)
 * Time: $(TIME)
 */

$arr = array(1,2,3);

$newArr = combine_array($arr);
print_r($newArr);die;

function combine_array($arr, $index=0) {
    static $num = 0;
    $arr_len = count($arr);
    if($arr_len == $index) {
        ++$num;
        //输出每个结果
        echo $num.' '. join("+", $arr) . '<br/>';
    } else {
        for($i=$index; $i<$arr_len; $i++) {
            list($arr[$index], $arr[$i]) = array($arr[$i], $arr[$index]);
            combine_array($arr, $index+1);
            list($arr[$index], $arr[$i]) = array($arr[$i], $arr[$index]);
        }
    }
}
