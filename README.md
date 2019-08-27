# php_crumbs
将php常用到的小工具做成一个包,包括class以及trait

## trait

* CallWithFileLock 利用文件锁,并发防止争抢`callWithFileLock(mixed $class, string $method, array $param, string $FileLockName):mixed`
* Camel2Snake 字符串小驼峰转下划线   `camel2Snake($string $camel):string`
* PageParams 获取分页参数   `pageParams(string $pageName = 'page', string $pageSizeName = 'page_size', int $defaultPageSize = 10): array`


## class

* ParseDate 月份按周天数分段,判断是当前是当月第几周
* ObjectFactory 通过反射获得CLASS的DOC中的`@method $class $method(bool $refresh=true) static`获得类实例
* EmbedToImage 向图片嵌入文字或图片 gd库编译需要(jpeg,freetype)
