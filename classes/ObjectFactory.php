<?php
/**
 * author     jianglong
 * date       2019/8/27 上午10:18
 */

namespace crusj\php_crumbs\classes;


/**
 * author     jianglong
 * date       2019/8/13 上午9:10
 */

/**
 * Class ObjectFactory
 *
 * @author jianglong
 */
class ObjectFactory
{
    /**
     * @var array $instances
     */
    protected static $instances = [];
    /**
     * @var array $methods
     */
    protected static $methods = [];

    /**
     *
     * @param $name
     * @param $param
     * @return mixed
     * @throws \ReflectionException
     * @throws \Exception
     * @author jianglong
     */
    public static function __callStatic($name, $param)
    {
        //没有默认参数,默认为重新生成对象
        if (empty($param)) {
            $param[0] = true;
        }
        $ref = new \ReflectionClass(static::class);
        $namespace = $ref->getNamespaceName();
        if (isset(self::$instances[$namespace][$name]) && self::$instances[$namespace][$name] != null && $param[0] === false) {
            return self::$instances[$namespace][$name];
        }
        if (empty(self::$methods[$namespace])) {
            static::parseDocMethod($ref->getDocComment(), $namespace);
        }
        foreach (self::$methods[$namespace] as $method) {
            if ($name == $method['name']) {
                $type = $method['type'];
                if (strpos($method['type'], '\\') === false) {
                    $type = $ref->getNamespaceName() . '\\' . $type;
                }
                $class = new \ReflectionClass($type);
                if ($class->isInstantiable()) {
                    static::$instances[$namespace][$name] = $class->newInstance();
                    return static::$instances[$namespace][$name];
                } else {
                    throw new \Exception(sprintf("class %s can not instance", $method['type']));
                }
            }
        }
        throw new \Exception(sprintf("method %s can not found", $name));
    }

    private static function parseDocMethod(string $doc, string $namespace)
    {
        $find = '@method';
        $docs = explode(PHP_EOL, $doc);
        foreach ($docs as $line) {
            $position = strpos($line, $find);
            if ($position !== false) {
                $method = substr($line, $position + strlen($find));
                $methodPieces = preg_replace('/\n{2,}/', '\n', trim($method));
                $methodPieces = preg_replace('/\(.*\)/', '', $methodPieces);
                $methodPieces = explode(' ', $methodPieces);
                if (count($methodPieces) < 2) {
                    continue;
                }
                static::$methods[$namespace][] = [
                    'type' => trim($methodPieces[0]),
                    'name' => trim($methodPieces[1]),
                ];
            }
        }
    }
}

