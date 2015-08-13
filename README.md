# yaf-lib

yaf-lib 基于yaf的应用开发框架，
简单的mvc中间层继承补充，
常用缓存、数据库等驱动类，
添加widget组件模块，及layout插件，
rpc服务化，支持hprose、yar等，
支持xhprof性能调试。

##requirement

- php 5.3+
- php-yaf 2.3.3
- swoole 1.7.17
- hprose-php 1.5.4
- php-yar 1.2.4
- xhprof 0.9.4

##library

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

#### Search_Config
Search_Config类 可以根据配置处理搜索结果
 * getApp
 * getAppName
 * getAppId
 * getTable

#### Search_Segment
Search_Segment类 中文分词
 * init
 * close
 * query
 * queryOne
 * cutQuery
 * cutString

#### Search_Index
Search_Index类 用于添加索引
  * setIdPrefix
  * add
  * alert
  * delete

#### Search_Match
Search_Match类用于匹配搜索结果
  * call

#### Search_Database
Search_Database类 用于查询索引数据库中的原数据
  * get_doccount
  * get_data
  * get_termlist
  * select

##tools
#### xhprof
- xhprof-0.9.4

#### hprose-php
- hprose-php 1.5.4

##server
####index.php
yaf的CLI模式的入口文件，在CLI模式下可以开发强大的服务端程序，支持swoole和hprose
~~~sh
php index.php request_uri="/hprose/swooletcpserver"
~~~
####hprose_swoole_tcp.php
rpc服务，tcp协议，支持方法、对象方法、异步等调用
对象方法调用:
```php
//服务端代码
$server->add(new TestModel(),'','test');
//客户端调用
$client->test->one();
```


