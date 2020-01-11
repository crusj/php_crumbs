<?php

namespace crusj\php_crumbs\traits;

/**
 * 字符串驼峰转下划线,常用与变量名
 * Trait Camel2Snake
 * @package php_crumbs\traits
 * @author jianglong
 */
trait Camel2Snake
{
    /**
     * 驼峰转下划线
     * @param string $camel
     * @return string
     */
    public function camel2Snake(string $camel): string
    {
        return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $camel));
    }
}
