<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwGeneralLedger extends Model
{
    protected $table = 'vw_general_ledger';

    protected $guarded = ["id"];
    protected $primaryKey = 'id';
    

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
