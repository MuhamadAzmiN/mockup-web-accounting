<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Traits\AuthorizationChecker;
use Illuminate\Support\Facades\Auth;
use App\Models\Journal;
use App\Models\JournalEntries;
use App\Models\VwBalanceSheet;
use App\Models\VwGeneralLedger;
use Illuminate\Support\Facades\DB;
use PHPUnit\TestRunner\TestResult\Collector;

class FinanceController extends Controller
{
    use AuthorizationChecker;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
           $this->checkAuthorization(Auth::user(), ['manage_finance']);
           return $next($request);
        });
    }


public function index()
{
    $journals = collect();

    if (Auth::user()->company) {
        $journals = Journal::with(['company', 'branch'])
            ->where('company_id', Auth::user()->company_id)
            ->when(request()->filled('search'), function ($query) {
                $search = strtolower(request('search')); // ubah ke lowercase
                $query->whereHas('branch', function ($q) use ($search) {
                    $q->where(DB::raw('LOWER(name)'), 'like', '%' . $search . '%');
                });
            })
            ->when(request()->filled('start_date') && request()->filled('end_date'), function ($query) {
                $query->whereBetween('created_at', [
                    request('start_date') . ' 00:00:00',
                    request('end_date') . ' 23:59:59',
                ]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    } elseif (Auth::user()->hasRole('super-admin')) {
        $journals = Journal::with(['company', 'branch'])
            ->when(request()->filled('search'), function ($query) {
                $search = strtolower(request('search'));
                $query->whereHas('branch', function ($q) use ($search) {
                    $q->where(DB::raw('LOWER(name)'), 'like', '%' . $search . '%');
                });
            })
            ->when(request()->filled('start_date') && request()->filled('end_date'), function ($query) {
                $query->whereBetween('created_at', [
                    request('start_date') . ' 00:00:00',
                    request('end_date') . ' 23:59:59',
                ]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    return view('pages.finance.index', compact('journals'));
}






    public function details($id)
    {
        $journal = Journal::findOrFail($id);
        $items = VwGeneralLedger::where('journal_id', $id)
                            ->with(['account'])
                            ->get();

        $accounts = Account::all();

        
        return view('pages.finance.detail', compact('journal', 'items', 'accounts'));
    }


    



}
