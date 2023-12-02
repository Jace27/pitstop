<?php

namespace App\Admin\Controllers;

use App\Models\Sessions;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class SessionsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Пользователи';

    public function getModelClass(): string
    {
        return Sessions::class;
    }

    public function getSectionName(): string
    {
        return 'sessions';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Sessions());

        $grid->column('id', __('admin.ID'))->sortable();
        $grid->column('external_id', __('admin.Telegram ID'))->sortable();
        $grid->column('is_admin', __('admin.Is Admin'))->select([0 => 'Нет', 1 => 'Да'])->sortable();
        $grid->column('first_name', __('admin.First Name'))->sortable();
        $grid->column('last_name', __('admin.Last Name'))->sortable();
        $grid->column('username', __('admin.Username'))->sortable();
        $grid->column('status', __('admin.Status'))->select(Sessions::getStatuses())->sortable();
        $grid->column('created_at', __('admin.Created At'))->display(function ($var) {
            return date('H:i:s d.m.Y', strtotime($var));
        })->sortable();
        $grid->column('updated_at', __('admin.Updated At'))->display(function ($var) {
            return date('H:i:s d.m.Y', strtotime($var));
        })->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Sessions::findOrFail($id));

        $show->field('id', __('admin.ID'));
        $show->field('external_id', __('admin.Telegram ID'));
        $show->field('is_admin', __('admin.Is Admin'));
        $show->field('first_name', __('admin.First Name'));
        $show->field('last_name', __('admin.Last Name'));
        $show->field('username', __('admin.Username'));
        $show->field('status', __('admin.Status'))->using(Sessions::getStatuses());
        $show->field('created_at', __('admin.Created At'));
        $show->field('updated_at', __('admin.Updated At'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Sessions);

        $form->display('id', __('admin.ID'))->disable();
        $form->text('external_id', __('admin.Telegram ID'));
        $form->select('is_admin', __('admin.Is Admin'))->options([0 => 'Нет', 1 => 'Да']);
        $form->text('first_name', __('admin.First Name'));
        $form->text('last_name', __('admin.Last Name'));
        $form->text('username', __('admin.Username'));
        $form->select('status', __('admin.Status'))->options(Sessions::getStatuses());
        $form->display('created_at', __('admin.Created At'));
        $form->display('updated_at', __('admin.Updated At'));

        return $form;
    }
}
