<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $label
 * @property float|null $data
 * @property int|null $dataset_name
 * @property int $data_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed whereDataType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed whereDatasetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataFeed whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DataFeed extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $table = 'datafeeds';

    /**
     * Retrieves specific data types from the db
     *
     * @param int $dataType
     * @param string $field
     * @return mixed
     */
    public function getDataFeed(int $dataType, string $field = 'label', ?int $limit = null)
    {
        $query = $this->where('data_type', $dataType)
            ->where(function($q) use ($field){
                if ('label' == $field) {
                    $q->whereNotNull('label');
                }
            })->pluck($field)
            ->toArray();

        if (null !== $limit) {
            return array_slice($query, 0, $limit);
        }

        return $query;
    }

    /**
     * Counts a set of data based on the datatype
     *
     * @param int $dataType
     * @param int|null $limit
     * @return mixed
     */
    public function sumDataSet(int $dataType, ?int $dataset = null)
    {
        $query = $this->where('data_type', $dataType)
            ->where(function($q) use($dataset) {
                if (null !== $dataset) {
                    $q->where('dataset_name', $dataset);
                }
            })
            ->sum('data');

        return $query;
    }    
}
