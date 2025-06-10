<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $sale_id
 * @property int $product_id
 * @property int $quantity
 * @property string $sell_price
 * @property string $cost_price
 * @property string $subtotal
 * @property string $profit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Sales|null $sales
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereSellPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalesItem extends Model
{
    protected $table = 'sale_items';

    protected $guarded = ["id"];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
