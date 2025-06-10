<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $date
 * @property int $total_price
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SalesItem> $saleItems
 * @property-read int|null $sale_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sales extends Model
{
    protected $table = 'sales';

    protected $guarded = ["id"];


    public function saleItems()
    {
        return $this->hasMany(SalesItem::class, 'sale_id');
    }


    
}
