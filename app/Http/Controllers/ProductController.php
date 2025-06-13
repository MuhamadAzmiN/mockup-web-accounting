<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\AuthorizationChecker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
  use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    use AuthorizationChecker;
 

    public function index()
    {
        $this->checkAuthorization(Auth::user(), ['manage_product']);

        $products = collect();

        if (Auth::user()->company) {
            $products = Product::where('company_id', Auth::user()->company_id)
                ->where('type', 'inventory')
                ->orderBy('created_at', 'desc');
        } else if (Auth::user()->hasRole('super-admin')) {
            $products = Product::where('type', 'inventory');
        }

        if (request()->has('search') && request('search') != '') {
            $search = strtolower(request('search'));

            $products = $products->where(function ($query) use ($search) {
                $query->where(DB::raw('LOWER(product_code)'), 'like', '%' . $search . '%')
                    ->orWhere(DB::raw('LOWER(external_product_code)'), 'like', '%' . $search . '%')
                    ->orWhere(DB::raw('LOWER(product_name)'), 'like', '%' . $search . '%');
            });
        }

        $products = $products->paginate(10);

        return view('pages.products.index', compact('products'));
    }




    public function create()
    {
        $this->checkAuthorization(Auth::user(), ['manage_product']);
        $branches = collect();
        $user = Auth::user();
        if ($user && $user->company){
            $branches = $user->company->branches()->select('id', 'name')->get();
        }else if($user->hasRole('super-admin')){
            $branches = DB::table('branches')->select('id', 'name')->get();
        }
        return view('pages.products.create', compact('branches'));
    }


    public function store(Request $request)
{
    $this->checkAuthorization(Auth::user(), ['manage_product']);

    $request->validate([
        'product_code' => 'required|unique:products,product_code',
        'external_product_code' => 'nullable|unique:products,external_product_code',
        'product_name' => 'required|max:255',
        'stock' => 'required|integer|min:0',
        'selling_price' => 'required|numeric|min:0',
        'information' => 'nullable|string',
        'company_id' => (Auth::user()->hasRole('super-admin')) ? 'nullable' : 'required',
        // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $data = $request->all();

    if (!Str::startsWith($data['product_code'], 'PROD')) {
        $data['product_code'] = 'PROD' . $data['product_code'];
    }

    if (!empty($data['external_product_code']) && !Str::startsWith($data['external_product_code'], 'EXTPROD')) {
        $data['external_product_code'] = 'EXTPROD' . $data['external_product_code'];
    }

    try {
        Product::create($data);
        return redirect()->route('products')->with('success', 'Product created successfully!');
    } catch (\Illuminate\Database\QueryException $e) {
        // Tangkap kesalahan duplikat unique constraint
        if ($e->getCode() == '23505') {
            return redirect()->back()->withInput()->with('error', 'Product code or external product code already exists.');
        }

        // Tangkap kesalahan database lain
        return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}


    public function show(Product $product)
    {
        $this->checkAuthorization(Auth::user(), ['manage_product']);
        if ($product->type !== 'inventory') {
            abort(404);
        }
        return view('pages.products.detail', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->checkAuthorization(Auth::user(), ['manage_product']);
        return view('pages.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->checkAuthorization(Auth::user(), ['manage_product']);
        $request->validate([
            'product_code' => 'required|unique:products,product_code,' . $product->id,
            'external_product_code' => 'nullable|unique:products,external_product_code,' . $product->id,
            'product_name' => 'required|max:255',
            'stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'information' => 'nullable|string',
        ]);

        $data = $request->all();

        if (!Str::startsWith($data['product_code'], 'PROD')) {
            $data['product_code'] = 'PROD' . $data['product_code'];
        }

        if (!empty($data['external_product_code']) && !Str::startsWith($data['external_product_code'], 'EXTPROD')) {
            $data['external_product_code'] = 'EXTPROD' . $data['external_product_code'];
        }

        $product->update($data);

        return redirect()->route('products')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->checkAuthorization(Auth::user(), ['manage_product']);
      
        
        $product->delete();

        return redirect()->route('products')->with('success', 'Product deleted successfully!');
    }
}
