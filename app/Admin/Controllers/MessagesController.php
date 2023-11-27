<?php

namespace App\Admin\Controllers;

use App\Models\BotMessages;
use App\Models\Selectable\Tasks;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MessagesController extends AdminController
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

        $grid->column('id', __('Id'))->sortable();
        $grid->column('title', __('Title'))->sortable();
        $grid->column('task.title', __('Task'))->sortable();
        $grid->column('content_type', __('Content Type'))->select(BotMessages::getContentTypes())->sortable();
        $grid->column('text_content', __('Text Content'))->sortable();
        $grid->column('external_url', __('External Url'))->sortable();
        $grid->column('image', __('Image'));
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
        $show = new Show(BotMessages::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('task_id', __('Task'));
        $show->field('content_type', __('Content Type'));
        $show->field('text_content', __('Text Content'));
        $show->field('external_url', __('External Url'));
        $show->field('image', __('Image'));
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
        $form = new Form(new BotMessages());

        $form->text('title', __('Title'));
        $form->belongsTo('task_id', Tasks::class, __('Task'));
        $form->select('content_type', __('Content Type'))->options(BotMessages::getContentTypes())->default(1);
        $form->textarea('text_content', __('Text Content'));
        $form->text('external_url', __('External Url'));
        $form->image('image', __('Image'));

        return $form;
    }
}
