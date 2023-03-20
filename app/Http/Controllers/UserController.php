<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function signUpClient(Request $request){
        if (Auth::user()) {
            DB::beginTransaction();
            //validate fields
            $validator = Validator::make($request->all(), [
                'account_purpose' => 'in:private,enterprise,self_employed',
                'name' => 'required|string|max:255',
                'lastname' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:255',
                'net_income' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',

            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '00008', 'message' => 'error.title', "errors" => $validator->errors()], 401);
            }

            //client
            $client_user = User::create($request);
            if ($client_user) {
                //the rol id would depend on the seeder content we set for the table roles
                $user_role = UserRole::create(['user_id' => $client_user->id, 'role_id' => "2"]);
                DB::commit();
                return response()->json(['code' => '00000', 'message' => 'membership.response.ok', ], 200);
            } else {
                DB::rollBack();
                return response()->json(['code' => '00009', 'message' => 'membership.response.error'], 401);
            }
        }
        return response()->json(['code' => '00009', 'message' => 'error.auth'], 401);
    }
}
