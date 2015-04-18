# yaf-lib

yaf-lib 基于yaf框架，添加了自己的类库，使yaf更加丰满

##requirement

- php 5.3+

##library

#### Controller
Controller类继承Yaf_Controller_Abstract，控制器可以选择性的继承这个类，在类中实现了，layout、widget等初始化

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


##tools
#### xhprof
- xhprof-0.9.4


