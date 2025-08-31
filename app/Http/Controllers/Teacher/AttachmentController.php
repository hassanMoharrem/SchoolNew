<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttachmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Attachment::with('week')->where('week_id', $request->week_id); // إضافة العلاقة

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
            'attachments' => 'required|array',
            'attachments.*.name' => 'required|string',
            'attachments.*.description' => 'required|string|min:5|max:1000',
            'attachments.*.week_id' => 'required|exists:weeks,id',
            'attachments.*.file' => 'nullable|file',
            'attachments.*.visible' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $attachmentsData = [];

        foreach (request('attachments') as $input) {
            if (isset($input['file'])) {
                $input['file'] = $input['file']->store('files', 'public');
            }

            $attachmentsData[] = Attachment::create($input);
        }

        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' : 'Data Created',
            'success' => true,
            'data' => $attachmentsData,
        ]);
    }
        // public function store()
        // {
        //     $lang = request()->header('Accept-Language') ?? 'en';
        //     $validator = Validator::make(request()->all(), [
        //         'name' => 'required|string',
        //         'description' => 'required|string|min:5|max:1000',
        //         'week_id' => 'required|exists:weeks,id',
        //         'file' => 'nullable|image',
        //         'visible' => 'nullable|boolean'
        //     ]);
        //     if ($validator->fails()) {
        //         $response = [
        //             'status' => 400,
        //             'success' => false,
        //             'message' => $validator->errors(),
        //         ];
        //         return response()->json($response, 400);
        //     }

        //     $input = request()->all();

        //     if (isset($input['file'])) {
        //         $file = $input['file'];
        //         $input['file'] = $file->store('files', 'public');
        //     }

        //     $data = Attachment::create($input);
        //     return response()->json([
        //         'status' => 200,
        //         'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' :'Data Created',
        //         'success' => true,
        //         'data' => $data,
        //     ]);
        // }

    public function show($id)
        {
            $attachment = Attachment::find($id);

            if (!$attachment) {
                return response()->json(['message' => 'Attachment not found'], 404);
            }

            $data = $attachment->toArray();

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
            'week_id' => 'required|exists:weeks,id',
            'file' => 'nullable|file',
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

        $find = Attachment::find($id);
        $data = $request->except('file');
        $old_file = false;

        if($request->hasFile('file')){
            $file = $request->file('file');
            $data['file'] = $file->store('files','public');
            $old_file = $find->file;
        }
        if($old_file) {
            Storage::disk('public')->delete($old_file);
        }

        $find->update($data);
        $find->load('week');

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
            $find = Attachment::find($id);
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
