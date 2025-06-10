<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property int|null $branch_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class Account extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $company_id
 * @property string $code
 * @property string $name
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class Branch extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branch> $branches
 * @property-read int|null $branches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
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
	class DataFeed extends \Eloquent {}
}

namespace App\Models{
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
	class Journal extends \Eloquent {}
}

namespace App\Models{
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
	class JournalEntries extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $product_code
 * @property string|null $external_product_code
 * @property string $product_name
 * @property int|null $category_id
 * @property int $stock
 * @property string $selling_price
 * @property string|null $company_id
 * @property string|null $image
 * @property string|null $information
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $branch_id
 * @property string $type
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereExternalProductCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $date
 * @property int $total_price
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $company_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSales whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ProductSales extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $po_number
 * @property string $order_date
 * @property string $status
 * @property string $total_amount
 * @property string|null $company_id
 * @property int|null $created_by
 * @property int|null $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $product_id
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder wherePoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PurchaseOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $date
 * @property int $total_price
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SalesItem> $saleItems
 * @property-read int|null $sale_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sales whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Sales extends \Eloquent {}
}

namespace App\Models{
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
	class SalesItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $company_id
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App\Models{
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
	class VwBalanceSheet extends \Eloquent {}
}

namespace App\Models{
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
	class VwGeneralLedger extends \Eloquent {}
}

