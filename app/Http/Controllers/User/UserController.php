<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function profile()
    {
        $data = User::query()->where('id',auth('user')->id())->first();
        return response()->json($data);
    }

    public function update(Request $request){
        $lang = request()->header('Accept-Language') ?? 'en';
        $id = auth('user')->id();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:50|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable',
            'age' => 'required|integer',
            'image' => 'nullable|image',
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $find = User::find($id);
        $data = $request->except('image','password');
        $old_image = false;

        if($request->hasFile('image')){
            $file = $request->file('image');
            $data['image'] = $file->store('images','public');
            $old_image = $find->image;
        }

        if ($request->has('password') && !empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if($old_image) {
            Storage::disk('public')->delete($old_image);
        }

        // if ($request->input('visible') === "true") {
        //     $data['visible'] = 1 ;
        // } else if ($request->input('visible') === null){
        //     $data['visible'] = 0 ;
        // }
        $find->update($data);

        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم تحديث بنجاح' : 'Data updated',
            'success' => true,
            'data' => $find,
        ]);
    }

    public function destroy($id)
    {
        $lang = request()->header('Accept-Language') ?? 'en';
        try {
            $find = User::find($id);
            $find->delete();
            return response()->json([
                'status' => 200,
                'message' => $lang == 'ar' ? 'تم الحذف بنجاح' : 'Data deleted',
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $lang == 'ar' ? 'خطأ' : 'Error',
                'success' => false,
            ]);
        }
    }
}

