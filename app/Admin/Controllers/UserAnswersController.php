<?php

namespace App\Admin\Controllers;

use App\Models\Selectable\SessionsSelectable;
use App\Models\UserAnswers;
use App\Models\Selectable\BotMessagesSelectable;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserAnswersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ответы пользователей';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserAnswers());

        $grid->column('id', __('admin.Id'))->sortable();
        $grid->column('user.external_id', __('admin.User Id'))->sortable();
        $grid->column('message.title', __('admin.Message'))->sortable();
        $grid->column('answer', __('admin.Answer'))->sortable();
        $grid->column('correct', __('admin.Correct'))->display(function ($var) {
            if (is_null($var)) return 'Не проверено';
            return match ($var) {
                0 => 'Нет',
                1 => 'Да',
                default => 'Не проверено',
            };
        });
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
        $show = new Show(UserAnswers::findOrFail($id));

        $show->field('id', __('admin.Id'));
        $show->field('user.external_id', __('admin.User Id'));
        $show->field('message.title', __('admin.Message'));
        $show->field('answer', __('admin.Answer'));
        $show->field('correct', __('admin.Correct'));
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
        $form = new Form(new UserAnswers());

        $form->belongsTo('user_id', SessionsSelectable::class, __('admin.User Id'));
        $form->belongsTo('message_id', BotMessagesSelectable::class, __('admin.Message'));
        $form->textarea('answer', __('admin.Answer'));
        $form->select('correct', __('admin.Correct'))->options([false => 'Нет', true => 'Да']);

        return $form;
    }
}
