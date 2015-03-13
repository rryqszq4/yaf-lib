# yaf-lib

yaf-lib 基于yaf框架，添加了自己的类库，使yaf更加丰满

##requirement

- php 5.3+

##library

#### Controller
    Controller类继承Yaf_Controller_Abstract，控制器可以选择性的继承这个类，
    在类中实现了，layout、widget等初始化

#### Model
    Model类是数据层的抽象类，所有数据对象都可以继承它，Model可以从不同的data_center
    分发数据。

#### Widget
    

#### System_Mongo

#### System_Socket

