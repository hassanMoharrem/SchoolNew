<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StageSubjectTeacher;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
class StageSubjectTeacherController extends Controller
{
    public function index()
    {
        $teacher_id = auth('teacher')->id();
        $data = StageSubjectTeacher::with(['stage', 'subject', 'teacher'])->where('teacher_id',$teacher_id)->paginate(10);
        return response()->json($data);
    }

    public function show($id)
    {
        $item = StageSubjectTeacher::with(['stage', 'subject', 'teacher'])->find($id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($item);
    }

    public function store()
    {
        $teacher_id = auth('teacher')->id();
        $validator = Validator::make(request()->all(), [
            'stage_id' => 'required|exists:stages,id',
            'subject_id' => 'required|exists:subjects,id',
            // 'teacher_id' => 'required|exists:teachers,id',
        ]);
        $data = request()->all();
        $data['teacher_id'] = $teacher_id;

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }
        $data = StageSubjectTeacher::create($data);
        $data->load(['stage', 'subject', 'teacher']);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function update($id)
    {
        $item = StageSubjectTeacher::find($id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $validator = Validator::make(request()->all(), [
            'stage_id' => 'required|exists:stages,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }
        $item->update(request()->all());
        $item->load(['stage', 'subject', 'teacher']);
        return response()->json(['success' => true, 'data' => $item]);
    }
       public function showSubject($id)
    {
        $id = (int)$id;
        $subject = StageSubjectTeacher::with('stage','subject')->find($id);

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

    public function destroy()
    {
        $teacher_id = auth('teacher')->id();
        $stage_id = request('stage_id');
        $subject_id = request('subject_id');

        $item = StageSubjectTeacher::where('teacher_id', $teacher_id)
            ->where('stage_id', $stage_id)
            ->where('subject_id', $subject_id)
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->delete();
        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
    public function destroySubscribe($id)
    {
        $teacher_id = auth('teacher')->id();

        $item = StageSubjectTeacher::where('teacher_id', $teacher_id)
            ->where('id', $id)
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->delete();
        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
}
