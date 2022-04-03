<?php

namespace App\Http\Controllers;

use App\DataTables\Users\AuditTrailDataTable;
use App\DataTables\Users\PermissionsDataTable;
use App\DataTables\Users\RolesDataTable;
use App\DataTables\Users\UserDataTable;
use App\DataTables\Users\UserPermissionsDataTable;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable)
    {
        $roles = Role::all();
        return $dataTable->render('users.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(UserRequest $request)
    {
        $request->validated();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'gender' => $request->gender,
            'password' => bcrypt($request->password),
        ]);

        $role = Role::findById($request->role);
        $user->assignRole($role);

        return response()->json([
            'status' => 200,
            'message' => 'User Successfully Created'
        ]);
    }


    public function roles(RolesDataTable $dataTable)
    {
        $roles = Role::all();
        return $dataTable->render('users.role', [
            'roles' => $roles
        ]);
    }


    public function create_roles(Request $request)
    {
        $request->validate([
            'name'=> 'required|unique:roles'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Role Successfully Created'
        ]);
    }


    public function view_roles(Request $request, $role_id)
    {
        $roles = Role::findById($role_id);
        $permissions = Permission::all();
        return view('users.view_roles', [
            'role' => $roles,
            'permissions' => $permissions
        ]);
    }


    public function create_roles_permissions(Request $request)
    {
        $request->validate([
            'name'=> 'required|unique:permissions'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Permission Successfully Created'
        ]);
    }


    public function sync_roles_permissions(Request $request)
    {
        $role = Role::findById($request->role_id);
        $permissions = Permission::query()->whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return response()->json([
            'status' => 200,
            'message' => 'Permission Successfully Assigned to Role'
        ]);
    }


    public function permissions(PermissionsDataTable $dataTable)
    {
        return $dataTable->render('users.permission', [
        ]);
    }


    public function user_permissions(UserPermissionsDataTable $dataTable, $user_id, $role_id)
    {
        return $dataTable->with(['user_id' => $user_id, 'role_id' => $role_id])->render('users.permission', [
        ]);
    }


    public function assigned_permissions(PermissionsDataTable $dataTable)
    {
        return $dataTable->render('users.permission', [
        ]);
    }


    public function get(Request $request)
    {
        $user = User::where('id', '=', $request->id)->with('roles')->get();

        return response()->json([
            'status' => 200,
            'data' => $user
        ]);
    }


    public function edit(Request $request)
    {
        $user = User::find($request->id);
        $role = Role::findById($request->role);

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username'=> 'required|unique:users,username,'.$user->id,
            'email' => 'sometimes|nullable|email|unique:users,email,'.$user->id,
        ]);


        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'gender' => $request->gender,
        ]);

        $user->roles()->detach();
        $user->assignRole($role);

        return response()->json([
            'status' => 200,
            'message' => 'User Successfully Updated'
        ]);
    }


    public function audit(AuditTrailDataTable $dataTable)
    {
        return $dataTable->render('users.audit', [
        ]);
    }


    //UPDATE Password
    public function password(){
        return View('password');
    }

    public function updatePassword(Request $request){
        $rules = [
            'mypassword' => 'required',
            'password' => 'required|confirmed|min:6|max:18',
        ];

        $messages = [
            'mypassword.required' => 'Current password is required',
            'password.required' => 'New password is required',
            'password.confirmed' => 'Passwords do not match',
            'password.min' => 'Password is too short (minimum is 6 characters)',
            'password.max' => 'Password is too long (maximum is 18 characters)',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()){
            return redirect('password')->withErrors($validator);
        }
        else{
            if (Hash::check($request->mypassword, Auth::user()->password)){
                $user = new User;
                $user->where('email', '=', Auth::user()->email)
                    ->update(['password' => bcrypt($request->password)]);
                return redirect('/')->with('message', 'Password changed successfully')->with('m-class','alert-success');
            }
            else
            {
                return redirect('password')->with('message', 'Current password is invalid')->with('m-class','alert-danger');
            }
        }
    }
}
