<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $journal_id
 * @property int|null $account_id
 * @property string $debit
 * @property string $credit
 * @property string|null $source_event
 * @property string|null $source_ref_id
 * @property-read \App\Models\Account|null $account
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries whereJournalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries whereSourceEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntries whereSourceRefId($value)
 * @mixin \Eloquent
 */
class JournalEntries extends Model
{
    protected $table = 'journal_entries';
     public $timestamps = false;            // nonaktifkan timestamps

    protected $fillable = [
        'journal_id',
        'account_id',
        'debit',
        'credit',
        'source_event',
        'source_ref_id',
    ];


    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
