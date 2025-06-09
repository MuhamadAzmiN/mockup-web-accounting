<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\AuthorizationChecker; // Assuming you have this trait for authorization checks
use Illuminate\Support\Facades\Auth;

class PermissionMatrixController extends Controller
{
    use AuthorizationChecker;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
           $this->checkAuthorization(Auth::user(), ['manage_roles_permissions']);
           return $next($request);
        });
    }
    

      public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('pages.roles.index', compact('roles', 'permissions'));
    }

      public function update(Request $request)
    {
        $permissions = $request->input('permissions', []);
    
        // Get all roles for syncing
        $roles = Role::all();
        
        // For each role, sync its permissions
        foreach ($roles as $role) {
            if (isset($permissions[$role->id])) {
                // Validate that permissions exist before syncing
                $validPermissionIds = Permission::whereIn('id', $permissions[$role->id])->pluck('id')->toArray();
                
                // Sync valid permissions only
                $role->syncPermissions($validPermissionIds);
            } else {
                // No permissions selected, remove all
                $role->syncPermissions([]);
            }
        }
        
        return redirect()->back()
            ->with('success', 'Permissions updated successfully.');
    }
}
