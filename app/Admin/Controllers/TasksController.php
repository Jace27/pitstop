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

        $grid->column('id', __('ID'))->sortable();
        $grid->column('title', __('Title'))->sortable();
        $grid->column('ord', __('Ord'))->sortable();
        $grid->column('enabled', __('Enabled'))->sortable();
        $grid->column('created_at', __('Created At'))->display(function ($var) {
            return date('H:i:s d.m.Y', strtotime($var));
        })->sortable();
        $grid->column('updated_at', __('Updated At'))->display(function ($var) {
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

        $show->field('id', __('ID'));
        $show->field('title', __('Title'));
        $show->field('ord', __('Ord'));
        $show->field('enabled', __('Enabled'));
        $show->field('created_at', __('Created At'));
        $show->field('updated_at', __('Updated At'));

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

        $form->text('title', __('Title'));
        $form->number('ord', __('Ord'))->default(100);
        $form->switch('enabled', __('Enabled'))->default(1);

        return $form;
    }
}
