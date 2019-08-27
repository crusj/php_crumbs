<?php
/**
 * author     jianglong
 * date       2019/8/5 下午5:00
 */

/**
 * 文件锁
 * Trait FileLock
 * @author jianglong
 */

namespace php_crumbs\traits;

trait CallWithFileLock
{
    /**
     * 需要存放作为锁文件的目录
     * @var string $fileLockDir
     */
    protected $fileLockDir = '../runtime/lock';

    public function callWithFileLock($class, $method, $param, $lockFileName)
    {
        if (!is_dir($this->fileLockDir)) {
            mkdir($this->fileLockDir, 0777, true);
        }
        $resource = fopen('../runtime/lock/' . $lockFileName, 'w+');
        if (flock($resource, LOCK_EX)) {//排它锁
            $rsl = call_user_func_array([$class, $method], $param);
            flock($resource, LOCK_UN);//解锁
        }
        fclose($resource);
        return $rsl ?? null;
    }
}
