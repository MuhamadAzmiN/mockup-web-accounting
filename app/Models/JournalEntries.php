<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
