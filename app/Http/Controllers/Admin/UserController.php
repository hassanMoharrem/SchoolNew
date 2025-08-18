<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
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
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'age' => 'required|integer|min:10',
            'password' => 'required',
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

        // if ($input['visible'] == true) {
        //     $input['visible'] = 1 ;
        // } else if ($input['visible'] === null){
        //     $input['visible'] = 0 ;
        // }

        if (isset($input['image'])) {
            $file = $input['image'];
            $input['image'] = $file->store('images', 'public');
        }

        $input['password'] = Hash::make($input['password']);

        $data = User::create($input);
        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' :'Data Created',
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $data = $user->toArray();

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
            'name' => 'required|string|min:3|max:50|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable',
            'age' => 'required|integer',
            'image' => 'nullable|image',
            'visible' => 'nullable',
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
        if($old_image) {
            Storage::disk('public')->delete($old_image);
        }

        if ($request->has('password') && !empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }


        if ($request->input('visible') === 'true') {
            $data['visible'] = 1 ;
        } else if ($request->input('visible') === null){
            $data['visible'] = 0 ;
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
    // public function export(Request $request){
    // $spreadsheet = new Spreadsheet();
    // $sheet = $spreadsheet->getActiveSheet();

    // // رؤوس الأعمدة
    // $sheet->setCellValue('A1', 'ID');
    // $sheet->setCellValue('B1', 'Name');
    // $sheet->setCellValue('C1', 'Email');
    // $sheet->setCellValue('D1', 'Visible');

    // // تصميم للرؤوس
    // $headerStyle = [
    //     'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    //     'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
    // ];
    // $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

    // // بيانات المستخدمين
    
    //     $query = User::select('id', 'name', 'email','visible');
    //     if ($request->filled('name')) {
    //         $query->where('name', 'like', '%' . $request->name . '%');
    //     }
    //     if ($request->filled('visible') && $request->visible !== 1) {
    //         $query->where('visible', $request->visible);
    //     }
    //     $users = $query->get();
    //     $row = 2;

    // foreach ($users as $user) {
    //     $sheet->setCellValue("A{$row}", $user->id);
    //     $sheet->setCellValue("B{$row}", $user->name);
    //     $sheet->setCellValue("C{$row}", $user->email);
    //     $sheet->setCellValue("D{$row}", $user->visible);
    //     $row++;
    // }
    //  foreach (range('A', 'D') as $columnID) {
    //     $sheet->getColumnDimension($columnID)->setAutoSize(true);
    // }
    //     $sheet->getStyle('A1:D' . ($row - 1))
    //       ->getAlignment()
    //       ->setHorizontal(Alignment::HORIZONTAL_LEFT);

    // // حفظ مؤقت وإرسال للتحميل
    // $writer = new Xlsx($spreadsheet);
    // $filename = 'styled_users_' . now()->format('Ymd_His') . '.xlsx';
    // $tempPath = storage_path("app/{$filename}");
    // $writer->save($tempPath);

    // return Response::download($tempPath)->deleteFileAfterSend(true);
    // }
    public function export(Request $request)
{
    $filters = $request->only(['name', 'visible']);

    return Excel::download(new UsersExport($filters), 'users.xlsx');
}
}
