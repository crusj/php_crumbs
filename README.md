# php_crumbs
将php常用到的小工具做成一个包,包括class以及trait

## trait

* CallWithFileLock 利用文件锁,并发防止争抢`callWithFileLock(mixed $class, string $method, array $param, string $FileLockName):mixed`
* Camel2Snake 字符串小驼峰转下划线   `camel2Snake($string $camel):string`
* Page 获取分页参数,以及配合laravel的分页类实现数据表的分页，支持字段过滤和别名
* ReadableFileSize 字节转化为易读大小


## class

* ParseDate 月份按周天数分段,判断是当前是当月第几周
* ObjectFactory 通过反射获得CLASS的DOC中的`@method $class $method(bool $refresh=true) static`获得类实例
* EmbedToImage 向图片嵌入文字或图片 gd库编译需要(jpeg,freetype)
* FailResponse,SuccessResponse,Response,ResponseException,用来处理接口返回，只需要抛出SuccessResponse,和FailResponse,继承Response拓展异常类


## changelog

### 2020-01-11

* ParseDate 增加时间格式或时间戳转化为几分钟前、几小时前、几个月前的方法
* 更新相关的注释信息
* 增加traits将byte转化为可读的大小

### 2020-04-24 v1.0.0
* 修复生成Factory doc,windows平台无法正常解析文档问题

