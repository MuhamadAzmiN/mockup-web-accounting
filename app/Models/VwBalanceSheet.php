<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwBalanceSheet extends Model
{
    protected $table = 'vw_balance_sheet';
    protected $primaryKey = 'account_id'; // Sesuaikan dengan kolom unik di view

    // Kalau view read-only, bisa disable mass assignment
    protected $guarded = [];

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
