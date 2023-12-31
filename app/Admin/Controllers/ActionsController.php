<?php

namespace App\Admin\Controllers;

use App\Models\Actions;
use App\Models\Selectable\BotMessagesSelectable;
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

        $grid->column('id', __('admin.Id'))->sortable();
        $grid->column('text', __('admin.Text'))->sortable();
        $grid->column('message.title', __('admin.Message'))->sortable();
        $grid->column('next_message.title', __('admin.Next Message'))->sortable();
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
        $show = new Show(Actions::findOrFail($id));

        $show->field('id', __('admin.Id'));
        $show->field('text', __('admin.Text'));
        $show->field('message_id', __('admin.Message'));
        $show->field('next_message_id', __('admin.Next Message'));
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
        $form = new Form(new Actions());

        $form->textarea('text', __('admin.Text'));
        $form->belongsTo('message_id', BotMessagesSelectable::class, __('admin.Message'));
        $form->belongsTo('next_message_id', BotMessagesSelectable::class, __('admin.Next Message'));

        return $form;
    }
}
