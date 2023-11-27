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

        $grid->column('id', __('ID'))->sortable();
        $grid->column('external_id', __('Telegram ID'))->sortable();
        $grid->column('first_name', __('First Name'))->sortable();
        $grid->column('last_name', __('Last Name'))->sortable();
        $grid->column('username', __('Username'))->sortable();
        $grid->column('status', __('Status'))->select(Sessions::getStatuses())->sortable();
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
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Sessions::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('external_id', __('Telegram ID'));
        $show->field('first_name', __('First Name'));
        $show->field('last_name', __('Last Name'));
        $show->field('username', __('Username'));
        $show->field('status', __('Status'))->using(Sessions::getStatuses());
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
        $form = new Form(new Sessions);

        $form->display('id', __('ID'))->disable();
        $form->text('external_id', __('Telegram ID'));
        $form->text('first_name', __('First Name'));
        $form->text('last_name', __('Last Name'));
        $form->text('username', __('Username'));
        $form->select('status', __('Status'))->options(Sessions::getStatuses());
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        return $form;
    }
}
