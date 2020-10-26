<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use James\Sortable\SortableTrait;
use James\Sortable\Sortable;
class Film extends Model
{
    protected $fillable = ['display'];

    use SortableTrait;
    public $sortable=[
        'sort_field' => 'order_by',
        'sort_when_creating' => true,
    ];
}
