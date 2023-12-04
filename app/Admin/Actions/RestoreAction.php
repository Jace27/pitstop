<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class RestoreAction extends RowAction
{
    public $name = 'Restore';

    public function __construct()
    {
        $this->name = __('admin.Restore');
        parent::__construct();
    }

    public function handle(Model $model)
    {
        $model->restore();
        return $this->response()->success(__('admin.Restored'))->refresh();
    }
}
