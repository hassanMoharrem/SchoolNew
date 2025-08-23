<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('visible') && $request->visible !== 1) {
            $query->where('visible', $request->visible);
        }
        $data = $query->orderBy('id', 'desc')->paginate(10);
        return response()->json($data);
    }

    public function store()
    {
        $lang = request()->header('Accept-Language') ?? 'en';
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string',
            'description' => 'required|string|min:5|max:1000',
            'sub_description' => 'required|string|min:5|max:1000',
            'image' => 'nullable|image',
            'visible' => 'nullable|boolean'
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

        if (isset($input['image'])) {
            $file = $input['image'];
            $input['image'] = $file->store('images', 'public');
        }

        $data = Subject::create($input);
        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' :'Data Created',
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $data = $subject->toArray();

        $checkboxFields = ['visible'];
        foreach ($checkboxFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = (bool)$data[$field];
            }
        }
        return response()->json($data);
    }

    public function update(Request $request ,$id){
        $lang = request()->header('Accept-Language') ?? 'en';

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string|min:5|max:1000',
            'sub_description' => 'required|string|min:5|max:1000',
            'image' => 'nullable|image',
            'visible' => 'nullable|boolean'
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $find = Subject::find($id);
        $data = $request->except('image');
        $old_image = false;

        if($request->hasFile('image')){
            $file = $request->file('image');
            $data['image'] = $file->store('images','public');
            $old_image = $find->image;
        }
        if($old_image) {
            Storage::disk('public')->delete($old_image);
        }

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
            $find = Subject::find($id);
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
