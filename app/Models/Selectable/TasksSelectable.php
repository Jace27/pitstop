<?php

namespace App\Models\Selectable;

use App\Models\Tasks;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class TasksSelectable extends Selectable
{
    public $model = Tasks::class;

    public function make()
    {
        $this->column('id');
        $this->column('title');

        $this->filter(function (Filter $filter) {
            $filter->like('title');
        });
    }
}
