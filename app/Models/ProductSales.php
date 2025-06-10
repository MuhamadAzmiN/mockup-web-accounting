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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductSales extends Model
{
    protected $table = 'sales';
}
