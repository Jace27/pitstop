<?php

namespace App\Admin\Controllers;

use App\Models\BotMessages;
use App\Models\Selectable\BotMessagesSelectable;
use App\Models\Selectable\TasksSelectable;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BotMessagesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Сообщения';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BotMessages());

        $grid->column('id', __('admin.Id'))->sortable();
        $grid->column('slug', __('admin.Slug'))->display(function ($var) {
            return $var ? BotMessages::getSlugs()[$var] : null;
        });
        $grid->column('title', __('admin.Title'))->sortable();
        $grid->column('task.title', __('admin.Task'))->sortable();
        $grid->column('text_content', __('admin.Text Content'))->sortable();
        $grid->column('external_url', __('admin.External Url'))->sortable();
        $grid->column('image', __('admin.Image'))->display(function ($var) {
            $filename = $_SERVER['DOCUMENT_ROOT'].'/storage/'.$var;
            if (!is_file($filename)) return null;
            return '<img src="/storage/'.$var.'" style="max-width: 100%">';
        });
        $grid->column('wait_answer', __('admin.Wait answer'))->select([0 => 'Нет', 1 => 'Да']);
        $grid->column('next_message.title', __('admin.Next message'));
        $grid->column('prev_message.title', __('admin.Previous message'));
        $grid->column('wait_time', __('admin.Wait time'));
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
        $show = new Show(BotMessages::findOrFail($id));

        $show->field('id', __('admin.Id'));
        $show->field('slug', __('admin.Slug'));
        $show->field('title', __('admin.Title'));
        $show->field('task_id', __('admin.Task'));
        $show->field('text_content', __('admin.Text Content'));
        $show->field('external_url', __('admin.External Url'));
        $show->field('image', __('admin.Image'));
        $show->field('wait_answer', __('admin.Wait answer'));
        $show->field('next_message.title', __('admin.Next message'));
        $show->field('prev_message.title', __('admin.Previous message'));
        $show->field('wait_time', __('admin.Wait time'));
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
        $form = new Form(new BotMessages());

        $form->select('slug', __('admin.Slug'))->options(BotMessages::getSlugs());
        $form->text('title', __('admin.Title'));
        $form->belongsTo('task_id', TasksSelectable::class, __('admin.Task'));
        $form->textarea('text_content', __('admin.Text Content'));
        $form->text('external_url', __('admin.External Url'));
        $form->image('image', __('admin.Image'));
        $form->select('wait_answer', __('admin.Wait answer'))->options([0 => 'Нет', 1 => 'Да']);
        $form->belongsTo('next_message_id', BotMessagesSelectable::class, __('admin.Next message'));
        $form->belongsTo('prev_message_id', BotMessagesSelectable::class, __('admin.Previous message'));
        $form->number('wait_time', __('admin.Wait time'));

        return $form;
    }
}
