<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-12
 * Time: 下午5:30
 */
return array(
    'app' => 'bns',
    'app_name' => '剑灵',
    'app_id' => null,
    'tables' => array(
        'weapon','gem'
    ),
    'weapon' => array(
        'search_index'=>array('name','以下效果随机出现一种',
            'location',
            '职业','growth'),
        'primary_key' => '_id',
        'cn_name' => '武器',
        'url' => array("http://cha.17173.com/bns/weapon/{*}.html",'_id'),
        'title' => array('name'),
        'detail' => array('职业','以下效果随机出现一种','location','growth'),
        'image_url' => array('http://i1.17173cdn.com/z6po4a/YWxqaGBf/images/data/weapon/big/{*}.png','icon',70),
        'sort_id'=> 10201
    ),
    'gem' => array(
        'search_index'=>array('name','suit_name','count_3_effect','count_5_effect',
            'count_8_effect'
        ),
        'primary_key' => '_id',
        'cn_name' => '八卦牌',
        'url' => array("http://cha.17173.com/bns/gem/{*}.html",'_id'),
        'title' => array('name'),
        'detail' => array('suit_name','count_3_effect','count_5_effect',
            'count_8_effect'),
        'image_url' => array('http://i1.17173cdn.com/z6po4a/YWxqaGBf/images/data/gem/geml/{*}.png','icon',150),
        'sort_id'=> 10202
    ),


);