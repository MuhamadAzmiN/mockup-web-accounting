<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table = 'journals';

    protected $guarded = ["id"];

    public $timestamps = false;


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntries::class, 'journal_id');
    }
}
