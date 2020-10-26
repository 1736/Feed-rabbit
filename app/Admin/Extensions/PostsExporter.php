<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter; 

class PostsExporter extends ExcelExporter
{
    protected $fileName = '會員列表.xlsx';

    protected $columns = [
        'name'=>'姓名', 
        'username'=>'帳號',
        'created_at'=>'日期'
       
    ];
}