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
        'hero',
    ),
    'hero' => array(
        'search_index'=>array('name','title','othername','displayName',
                        'userskill','pkskill','editersaid'
                    ),
        'primary_key' => '_id',
        'cn_name' => '英雄',
        'url' => "http://cha.17173.com/lol/heros/details/{*}.html"
    ),

);