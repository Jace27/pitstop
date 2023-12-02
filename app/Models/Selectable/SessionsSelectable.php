<?php

namespace App\Models\Selectable;

use App\Models\Sessions;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class SessionsSelectable extends Selectable
{
    public $model = Sessions::class;

    public function make()
    {
        $this->column('id');
        $this->column('external_id');
        $this->column('username');

        $this->filter(function (Filter $filter) {
            $filter->like('external_id');
            $filter->like('username');
        });
    }
}
