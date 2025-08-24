<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $teacher = Teacher::where('email', $request->email)->first();

        if (!$teacher || !Hash::check($request->password, $teacher->password)) {
            return response()->json(['message' => 'Error Email Or password'], 401);
        }

        $token = $teacher->createToken('teacher-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'teacher' => $teacher,
        ]);
    }

    public function register(Request $request)
    {
        $lang = request()->header('Accept-Language') ?? 'en';
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'password' => 'required|string|min:8',
            'birthday' => 'required|date', // date of birth
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $input = request()->all();
        $input['password'] = Hash::make($input['password']);

        $data = Teacher::create($input);

        $token = $data->createToken('teacher-token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' :'Data Created',
            'success' => true,
            'data' => $data,
            'token' => $token,
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
