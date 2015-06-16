<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-6
 * Time: 上午11:48
 */
return array(
    'app' => 'lol',
    'app_name' => '英雄联盟',
    'app_id' => null,
    'tables' => array(
        'hero','item','skill'
    ),
    'hero' => array(
        'search_index'=>array('name','title','othername','displayName','editersaid',
                        #'useskill','pkskill'
                    ),
        'primary_key' => '_id',
        'cn_name' => '英雄',
        'url' => array("http://cha.17173.com/lol/heros/details/{*}.html",'_id'),
        'title' => array('displayName','title'),
        'detail' => array('name','othername','editersaid'),
        'image_url' => array('http://i1.17173cdn.com/1tx6lh/YWxqaGBf/images/hero_skin/{*}_0.jpg','name',150),
        'sort_id' => 10101
    ),
    'item' => array(
        'search_index'=>array('name','basicattribute'),
        'primary_key' => '_id',
        'cn_name' => '物品 装备',
        'url' => array('http://cha.17173.com/lol/items/{*}.html','_id'),
        'title' => array('name'),
        'detail' => array('basicattribute'),
        'image_url' => array('http://i1.17173cdn.com/1tx6lh/YWxqaGBf/images/item/{*}','itemicon',80),
        'sort_id' => 10102
    ),
    'skill' => array(
        'search_index' => array('name','description','skillflow'),
        'primary_key' => '_id',
        'cn_name' => '技能',
        'url' => array('http://cha.17173.com/lol/heros/details/{*}.html','champion'),
        'title' => array('name'),
        'detail' => array('description','skillflow'),
        'image_url' => array('http://i1.17173cdn.com/1tx6lh/YWxqaGBf/images/abilities/{*}','iconPath',70),
        'sort_id' => 10103
    )

);