<?php
//配置文件
return [
    'template'      => [
        // 模板路径
        'view_path' => '../themes/admin/',
    ],
    //分页配置
    'paginate'      => [
        'type'      => 'page\Zui',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
];
