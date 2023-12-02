<?php

namespace App\Admin\Controllers;

use App\Models\Tasks;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TasksController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Задания';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tasks());

        $grid->column('id', __('admin.ID'))->sortable();
        $grid->column('number', __('admin.Number'))->sortable();
        $grid->column('title', __('admin.Title'))->sortable();
        $grid->column('enabled', __('admin.Enabled'))->sortable();
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
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Tasks::findOrFail($id));

        $show->field('id', __('admin.ID'));
        $show->field('number', __('admin.Number'));
        $show->field('title', __('admin.Title'));
        $show->field('enabled', __('admin.Enabled'));
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
        $form = new Form(new Tasks());

        $form->number('number', __('admin.Number'));
        $form->text('title', __('admin.Title'));
        $form->switch('enabled', __('admin.Enabled'))->default(1);

        return $form;
    }
}
