<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $guarded = ["id"];


    public function saleItems()
    {
        return $this->hasMany(SalesItem::class, 'sale_id');
    }


    
}
