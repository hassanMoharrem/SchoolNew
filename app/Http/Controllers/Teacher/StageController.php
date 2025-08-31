<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Subject;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function index(Request $request)
    {
        $query = Stage::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('visible') && $request->visible !== 1) {
            $query->where('visible', $request->visible);
        }
        $data = $query->orderBy('id', 'desc')->paginate(10);
        return response()->json($data);
    }
    public function getSubjects($id)
    {
        $teacher_id = auth('teacher')->id();
        $subjects = Subject::where('stage_id', $id)->paginate(4);

        // إضافة حالة الاشتراك لكل مادة
        $subjects->getCollection()->transform(function ($subject) use ($teacher_id) {
            $isSubscribed = \App\Models\StageSubjectTeacher::where('teacher_id', $teacher_id)
                ->where('subject_id', $subject->id)
                ->exists();
            $subject->is_subscribed = $isSubscribed;
            return $subject;
        });

        // if ($subjects->isEmpty()) {
        //     return response()->json(['message' => 'Stage not found'], 404);
        // }
        return response()->json($subjects);
    }
}
