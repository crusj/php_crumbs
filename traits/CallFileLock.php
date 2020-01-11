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

namespace crusj\php_crumbs\traits;

trait CallWithFileLock
{
    /**
     * 需要存放作为锁文件的目录
     * @var string $fileLockDir
     */
    protected $fileLockDir = '../runtime/lock';

    /**
     * 调取方法使用文件锁
     * @param $class 类实例
     * @param string $method 类实例方法
     * @param array $param 类实例方法参数
     * @param string $lockFileName 文件锁名称，不同场景的锁名字不要重复
     * @return mixed|null
     */
    public function callWithFileLock($class, string $method, array $param, string $lockFileName)
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
