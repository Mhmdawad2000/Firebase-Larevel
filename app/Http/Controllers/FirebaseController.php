<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FirebaseController extends Controller
{
    protected $database;

    // تعديل الـ Constructor
    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $firebase->createDatabase();
    }

    // عرض جميع المستخدمين
    public function index()
    {
        $ref = $this->database->getReference('users');
        $users = $ref->getValue();
        return view('users.index', ['users' => $users]);
    }

    // عرض نموذج إضافة مستخدم
    public function create()
    {
        return view('users.create');
    }

    // تخزين مستخدم جديد مع التحقق من البريد الإلكتروني
    public function store(Request $request)
    {
        //1. التحقق من البيانات
        $data = $request->validate([
            'firstName' => 'required|regex:/^[\p{Arabic}a-zA-Z\s]+$/u|min:3|max:20',
            'lastName' => 'required|regex:/^[\p{Arabic}a-zA-Z\s]+$/u|min:3|max:20',
            'email' => 'required|email',
            'password' => 'required|string|min:5|confirmed',
        ]);

        //2. التحقق من البريد الإلكتروني إذا كان مسجلاً بالفعل
        $usersRef = $this->database->getReference('users')
            ->orderByChild('email')
            ->equalTo($data['email'])
            ->getValue();
        if (!empty($usersRef)) {
            return view('users.create')->with('error', 'The email already token');
        }

        //3. تشفير كلمة المرور
        $data['password'] = Hash::make($data['password']);

        //4. إضافة المستخدم إلى Firebase
        $newUser = $this->database->getReference('users')->push([
            'firstName' => ucwords($data['firstName']),
            'lastName' => ucwords($data['lastName']),
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return redirect()->route('users');
    }

    // عرض نموذج تعديل مستخدم
    public function update($id)
    {
        $user = $this->database->getReference("users/{$id}")->getValue();
        return view('users.update', ['user' => $user, 'key' => $id]);
    }
    // تعديل  المستخدم
    public function edit(Request $request, $id)
    {
        $data = $request->validate([
            'firstName' => 'required|regex:/^[\p{Arabic}a-zA-Z\s]+$/u|min:3|max:20',
            'lastName' => 'required|regex:/^[\p{Arabic}a-zA-Z\s]+$/u|min:3|max:20',
        ]);
        $user = $this->database->getReference("users/{$id}")->getValue();
        if (!$user) {
            return redirect()->route('users')->with('error', 'User not founded');
        }
        $user = $this->database->getReference("users/{$id}")->update(
            [
                'firstName' => ucwords($data['firstName']),
                'lastName' => ucwords($data['lastName']),
            ]
        );
        return redirect()->route('users')->with('msg', 'User updeted');;
    }


    // حدف مستخدم

    public function delete($id) {
        
    //  التحقق مما إذا كان المستخدم موجودًا
    $userRef = $this->database->getReference("users/{$id}")->getValue();
    if (!$userRef) {
        return redirect()->route('users');
    }

    //  حذف المستخدم
    $this->database->getReference("users/{$id}")->remove();

    return redirect()->route('users');
    }
}
