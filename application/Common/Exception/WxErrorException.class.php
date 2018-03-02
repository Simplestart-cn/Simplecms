<?php
/**
 * Created by PhpStorm.
 * User: davidtall
 * Date: 2015/10/7
 * Time: 11:37
 */

namespace Common\Exception;


//use Think\Exception;

use Exception;

class WxErrorException extends Exception
{
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0) {
        // 自定义的代码

        // 确保所有变量都被正确赋值
        parent::__construct($message, $code);
    }

    // 自定义字符串输出的样式 */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {
        echo "这一异常的一个自定义功能\n";
    }


}