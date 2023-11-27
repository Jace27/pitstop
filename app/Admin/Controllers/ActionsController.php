<?php

namespace App\Admin\Controllers;

use App\Models\Actions;
use App\Models\Selectable\Messages;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ActionsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Действия';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Actions());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('text', __('Text'))->sortable();
        $grid->column('message.title', __('Message'))->sortable();
        $grid->column('next_message.title', __('Next Message'))->sortable();
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
        $show = new Show(Actions::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('text', __('Text'));
        $show->field('message_id', __('Message'));
        $show->field('next_message_id', __('Next Message'));
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
        $form = new Form(new Actions());

        $form->textarea('text', __('Text'));
        $form->belongsTo('message_id', Messages::class, __('Message'));
        $form->belongsTo('next_message_id', Messages::class, __('Next Message'));

        return $form;
    }
}
