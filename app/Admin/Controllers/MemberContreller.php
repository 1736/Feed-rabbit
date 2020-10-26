<?php

namespace App\Admin\Controllers;

use App\Member;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Tenant\ImportTenant;
use App\Admin\Extensions\PostsExporter;
class MemberContreller extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '會員管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Member());

        //序號
        $grid->number('#');
        $grid->rows(function ($row, $number) {
            $row->column('number', $number+1);
        });
        $grid->column('name', __('姓名'));
        $grid->column('username', __('帳號'));
        $grid->column('created_at', __('創建時間'))->display(function(){
            $id=$this->getRouteKey();
            $date=Member::select('created_at')->where('id',$id)->first();
            return date($date['created_at']);
        });
        $grid->column('updated_at', __('更新時間'))->display(function(){
            $id=$this->getRouteKey();
            $date=Member::select('updated_at')->where('id',$id)->first();
            return date($date['updated_at']);
        });
        $grid->exporter(new PostsExporter());
        // $grid->export(function ($export) {
 
        //     $export->filename('Member');
        //     $export->only(['name', 'username','password','created_at']);
        //     $export->column('password', function ($value, $original) {
        //         return $value;
        //     });
        // });
        // 將匯入操作加入到表格的工具條中
        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ImportTenant());
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
        $show = new Show(Member::findOrFail($id));

        $show->field('name', __('姓名'));
        $show->field('username', __('帳號'));
        $show->field('password', __('密碼'));
        $show->field('created_at', __('創建時間'));
        $show->field('updated_at', __('更新時間'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Member());
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
        $form->text('name', __('姓名'));
        $form->text('username', __('帳號'));
        $form->password('password', __('密碼'));
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = encrypt($form->password);
            }
        });
        return $form;
    }
}
