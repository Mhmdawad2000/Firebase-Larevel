<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Hash;

class FirebaseController extends Controller
{
    protected $database;

    // firebase connection
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    //-- Index--//
    // عرض جميع المستخدمين
    public function index()
    {
        $users = Cache::remember('users', 60, function () {
            return $this->database->getReference('users')->getValue();
        });
        return view('users.index', ['users' => $users]);
    }


    //-- Create--//
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
        $existingEmails = Cache::remember('users_emails', 60, function () {
            $users = $this->database->getReference('users')->getValue();
            return array_column($users ?? [], 'email');
        });

        if (in_array($data['email'], $existingEmails)) {
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

        //5. حذف الكاش وإعادة تحميل البيانات
        Cache::forget('users');
        Cache::forget('users_emails');
        return redirect()->route('users');
    }


    //-- Update--//
    // عرض نموذج تعديل مستخدم
    public function update($id)
    {
        $user = $this->database->getReference("users/{$id}")->getValue();
        return view('users.update', ['user' => $user, 'key' => $id]);
    }
    // تعديل  المستخدم
    public function edit(Request $request, $id)
    {
        //1. التحقق من البيانات المستلمة
        $data = $request->validate([
            'firstName' => 'required|regex:/^[\p{Arabic}a-zA-Z\s]+$/u|min:3|max:20',
            'lastName' => 'required|regex:/^[\p{Arabic}a-zA-Z\s]+$/u|min:3|max:20',
        ]);
        $userRef = $this->database->getReference("users/{$id}");
        $user = $userRef->getValue();
        //2. التحقق من ان المستخدم موجود
        if (!$user) {
            return redirect()->route('users')->with('error', 'User not found');
        }
        //3. تعديل المستخدم اذا وجد
        $userRef->update([
            'firstName' => ucwords($request->input('firstName')),
            'lastName' => ucwords($request->input('lastName')),
        ]);
        //4.  حذف الكاش وإعادة تحميل البيانات
        Cache::forget('users');

        return redirect()->route('users')->with('msg', 'User updated');
    }


    //-- Delete--//
    // حدف مستخدم
    public function delete($id)
    {
        //1. التحقق اذا المستخدم موجود
        $userRef = $this->database->getReference("users/{$id}");
        if (!$userRef->getValue()) {
            return redirect()->route('users');
        }
        //2. حذف المستخدم ان وجد
        $userRef->remove();
        // 3 حذف الكاش لضمان التحديث الفوري
        Cache::forget('users');

        return redirect()->route('users');
    }
}
