<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $customer = auth()->guard('api_customer')->user();
        // dd($customer);

        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'password' => 'confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->password == "") {

            //update user without password
            $customer->update([
                'name'      => $request->name,
                'email'     => $request->email,
            ]);
        }

        //update user with new password
        $customer->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        if ($customer) {
            //return success with Api Resource
            return new ProfileResource(true, 'Data Customer Berhasil Diupdate!', $customer);
        }

        //return failed with Api Resource
        return new ProfileResource(false, 'Data Customer Gagal Diupdate!', null);
    }
}
