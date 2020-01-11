<?php
/**
 * author crusj
 * date   2019/11/25 3:17 下午
 */


namespace crusj\php_crumbs\traits;

Trait ReadableFileSize
{
    /**
     * @param int $bytes 字节数
     * @param int $decimals 保留小数位
     * @return string
     */
    public function readAbleFileSize(int $bytes, $decimals = 2): string
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}

