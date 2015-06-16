<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-14
 * Time: 下午8:59
 */

return array(
    'app' => 'hs',
    'app_name' => '炉石传说',
    'app_id' => null,
    'tables' => array(
        'card_zhcn'
    ),
    'card_zhcn' => array(
        'search_index'=>array('name','card_name','card_name_en',
        'card_text_in_hand','flavor_text','artist_name'),
        'primary_key' => '_id',
        'cn_name' => '卡牌',
        'url' => array("http://cha.17173.com/hs/info/card_zhcn/{*}",'_id'),
        'title' => array('name'),
        'detail' => array('card_name','card_name_en',
            'card_text_in_hand','flavor_text','artist_name'),
        'image_url' => array('http://i1.17173cdn.com/8hpoty/YWxqaGBf/images/resource/new_middler/{*}.png','card_id',150),
        'sort_id'=> 10301
    ),



);