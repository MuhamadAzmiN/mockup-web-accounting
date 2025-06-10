<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int|null $account_id
 * @property string|null $account_code
 * @property string|null $account_name
 * @property int|null $journal_id
 * @property string|null $journal_date
 * @property string|null $journal_description
 * @property int|null $company_id
 * @property int|null $branch_id
 * @property string|null $debit
 * @property string|null $credit
 * @property string|null $running_balance
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereAccountCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereJournalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereJournalDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereJournalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VwGeneralLedger whereRunningBalance($value)
 * @mixin \Eloquent
 */
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
