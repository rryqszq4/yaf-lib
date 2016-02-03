# yaf-lib

yaf-lib 基于yaf的应用开发框架，
简单的mvc中间层继承补充，
常用缓存、数据库等驱动类，
添加widget组件模块，及layout插件，
rpc服务化，支持jsonrpc、hprose、yar等，
composer依赖包支持，
支持xhprof性能调试。

##requirement

- php 5.3+
- php-yaf 2.3.3
- phpredis 2.2.7
- memcache 2.2.7
- zmq 1.1.2
- xapian-bindings 1.2.21
- scws 1.2.2
- swoole 1.7.17
- jsonrpc 0.0.9
- hprose-php 1.5.4
- php-yar 1.2.4
- xhprof 0.9.4
- php-yajl 0.0.1
- php-cjson 0.0.1

##src/controllers
#### Madclient
Madclient是php实现的MadzMQ消息队列的客户端，通过msg协议与madbroker通信，异步发送消息。

##src/bootstrap
bootstrap是yaf的引导程序，这里进行拆分，如果工作的web进入cgi模式，如果工作在命令行进入cli模式。

##src/library

#### Controller
Controller类继承Yaf_Controller_Abstract，控制器可以选择性的继承这个类，在类中实现了，layout、widget等初始化。

#### Sontroller
Sontroller类继承Yaf_Controller_Abstract，工作在cli模式下。

#### Model
Model类是数据层的抽象类，所有数据对象都可以继承它，Model可以从不同的data_center分发数据。

#### Widget
Widget类是组件的基类，widgets目录下的组件类都继承它，widgets/views目录是相应组件的view。
views中通过$widget方法调用组件
```php
$widget('IndexWidget',
    array('class_key'=>'item_condition',
        'output'=>'html',
        'query'=>array(),
        'assist_data'=>array(
            'one'=>'one'
        )
    )
);
```

#### Zmq_Msg
Zmq_Msg是基于ZeroMQ的一种简单msg消息协议的php实现

### Zmq_Kvmsg
Zmq_Kvmsg是基于ZeroMQ的一种简单key-value消息协议的php实现

#### Core_Processpool
Core_Processpool类封装了一个进程池。

#### System_Mongo
System_Mongo类封装了mongodb的客户端接口。
* conn 用于连接mongo
* selectDB 选择数据库
* selectCollection 选择文档
* findOne 获取一条数据
* find 获取数据列表
* select 选择数据可以排序或区间
* insert 添加数据
* update 更新数据
* close()关闭连接

#### System_Socket
System_Socket类对网络socket进行封装

#### System_Memcache
System_Memcache类封装了Memcached的客户端接口
  * ::set 设置缓存
  * ::get 获取缓存
  * ::delete 删除缓存
  * ::flush 清空
  * ::increment 原子计数加
  * ::decrement 原子计数减

#### System_Redis
System_Redis类封装redis客户端驱动

#### System_Mysqlpdo
System_Mysqlpdo类封装了pdo_mysql的操作接口
  * ::bind
  * ::bindmore
  * ::query
  * ::lastInsertId
  * ::column
  * ::row
  * ::single

#### System_Log
System_Log类封装了日志处理类

#### System_Sort
System_Sort类封装了一些经典得排序算法
  * ::insertion 插入排序
  * ::selection 选择排序
  * ::bubble    冒泡排序
  * ::merge     归并排序

#### Search_Segment
Search_Segment类 中文分词
 * init         分词器初始化
 * close        关闭
 * query        获取原数据列表
 * queryOne     获取单个原数据
 * cutQuery     切分原数据
 * cutString    切分字符串

#### Search_Index
Search_Index类 用于添加索引
  * setIdPrefix
  * add         建立索引数据
  * alert       更新索引数据
  * delete      删除索引数据

#### Search_Match
Search_Match类用于匹配搜索结果
  * call    执行匹配

#### Search_Database
Search_Database类 用于查询索引数据库中的原数据
  * get_doccount    获取文档总数
  * get_data        获取单个文档原数据
  * get_termlist    获取单个文档语词
  * select          获取数据列表

#### Search_Config
Search_Config类 可以根据配置处理搜索结果
 * getApp       获取app
 * getAppName   获取app名称
 * getTableName 获取标名称
 * formatTitle  格式化标题
 * formatDetail 格式化详情
 * formatUrl    格式化地址栏
 * formatImage  格式化图片

##bin
#### main
服务端程序，根据入口文件名称找到MainController,然后执行mainAction方法;
yaf的CLI模式的入口文件，在CLI模式下可以开发强大的服务端程序
```sh
./bin/main
```

#### madserver
madserver是php实现的MadzMQ消息队列的服务端，其作为订阅者订阅来自madbroker的，协议为kvmsg.
```sh
./bin/madserver
```

####hprose_swoole_tcp.php
rpc服务，tcp协议，支持方法、对象方法、异步等调用
对象方法调用:
```php
//服务端代码
$server->add(new TestModel(),'','test');
//客户端调用
$client->test->one();
```

##src/tools
#### xapian
- xapian-bindings 1.2.21

#### xhprof
- xhprof-0.9.4

#### hprose-php
- hprose-php 1.5.4



