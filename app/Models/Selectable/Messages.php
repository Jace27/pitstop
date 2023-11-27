<?php

namespace App\Models\Selectable;

use App\Models\BotMessages;
use Encore\Admin\Grid\Filter;

class Messages extends \Encore\Admin\Grid\Selectable
{
    public $model = BotMessages::class;

    /**
     * @inheritDoc
     */
    public function make()
    {
        $this->column('id');
        $this->column('title');
        $this->column('task_id');

        $this->filter(function (Filter $filter) {
            $filter->like('title');
        });
    }
}
