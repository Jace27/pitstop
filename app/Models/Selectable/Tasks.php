<?php

namespace App\Models\Selectable;

use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class Tasks extends Selectable
{
    public $model = \App\Models\Tasks::class;

    public function make()
    {
        $this->column('id');
        $this->column('title');

        $this->filter(function (Filter $filter) {
            $filter->like('title');
        });
    }
}
