<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int|null $account_id
 * @property string|null $account_code
 * @property string|null $account_name
 * @property string|null $account_type
 * @property int|null $company_id
 * @property int|null $branch_id
 * @property string|null $total_debit
 * @property string|null $total_credit
 * @property string|null $balance
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JournalEntries> $journalEntries
 * @property-read int|null $journal_entries_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereAccountCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereTotalCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwBalanceSheet whereTotalDebit($value)
 * @mixin \Eloquent
 */
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
