<?php

namespace App\Admin\Controllers;

use App\Film;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FilmContreller extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '影片列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $states=[
            'on' => ['value' => 1, 'text' => '啟用', 'color' => 'primary'],
            'off'=> ['value' => 0, 'text' => '停用', 'color' => 'default'],
        ];
        $grid = new Grid(new Film());

        $grid->model()->orderBy('order_by','desc');
        $grid->disableExport();
        $grid->disableColumnSelector();
        $grid->filter(function($filter){
            $filter->column(5, function ($filter) {
                $filter->disableIdFilter();
               
                $display=[
                    '0'=>'停用',
                    '1'=>'啟用'
                ];
                $filter->disableIdFilter();
                $filter->in('display','狀態')->multipleSelect($display);
                $filter->like('name','標題');
            });
            $filter->column(5, function ($filter) {
                $type=[
                    '0'=>'同步',
                    '1'=>'不同步'
                ];
                $filter->in('type','分類')->multipleSelect($type);
                $filter->between('updated_at','更新時間')->date();
            });
        });
        $grid->column('display_mark', __('狀態'))->switch($states);
        $grid->status('順序')->sortableColumn(Member::class);
        $grid->column('name', __('名稱'));
        $grid->column('href', __('Youtube影片'))->display(function($url){
            $parts = parse_url($url);
            if(isset($parts['query'])){
                parse_str($parts['query'], $qs);
                if(isset($qs['v'])){
                    $href=($qs['v']) ;
                }else if(isset($qs['vi'])){
                    $href=($qs['vi']) ;
                }
                return "<iframe width='250' height='140' src='https://www.youtube.com/embed/$href' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                }
            });
        $grid->column('type', __('分類'))->display(function($type){
            if($type=="0"){
                return "同步";
            }else{
                return "不同步";
            }
        });
        $grid->column('updated_at', __('更新日期'))->display(function(){
            $id=$this->getRouteKey();
            $date=Film::select('updated_at')->where('id',$id)->first();
            return date($date['updated_at']);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Film::findOrFail($id));

        $show->field('name', __('名稱'));
        $show->field('href', __('網址'));
        $show->field('type', __('分類'))->as(function($type){
            if($type==0){
                return "同步";
            }else{
                return "不同步";
            }
        });
        $show->field('ontime', __('開始時段'));
        $show->field('offtime', __('結束時段'));
        $show->field('long', __('影片長度'));
        $show->field('total', __('點擊率'));
        $show->field('description', __('描述'));
        $show->field('content', __('內容'))->unescape()->as(function($content){
            return $content;
        });
        $show->field('created_at', __('創建時間'));
        $show->field('updated_at', __('更新日期'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Film());
        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
            // 去掉`查看`按钮
            $tools->disableView();
        }); 
        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();

        });
        $types=[
            '0'=>'同步',
            '1'=>'不同步'
        ];
        $form->switch('display_mark', __('狀態'))->default('1');
        $form->text('name', __('名稱'))->rules('required|max:250')->help('字數限制:250字元');
        $form->url('href', __('網址'))->rules('required|max:250')->help('字數限制:100字元');
        $form->select('type', __('分類'))->options($types)->rules('required');
        $form->datetime('ontime', __('開始時段'))->rules('required');
        $form->datetime('offtime', __('結束時段'))->rules('required');
        $form->time('long', __('影片長度'))->rules('required');
        $form->textarea('description', __('描述'))->rules('required|max:500')->help('字數限制:500字元');
        $form->ckeditor('content', __('內容'))->rules('required|max:500000')->help('字數限制:500000字元');

        return $form;
    }
}
