<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $journal_date
 * @property string|null $description
 * @property string $created_at
 * @property string|null $created_by
 * @property int|null $company_id
 * @property int|null $branch_id
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JournalEntries> $journalEntries
 * @property-read int|null $journal_entries_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereJournalDate($value)
 * @mixin \Eloquent
 */
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
