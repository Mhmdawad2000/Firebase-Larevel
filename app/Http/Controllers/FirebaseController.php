<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Hash;

class FirebaseController extends Controller
{
    protected $database;

    // Firebase connection
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    //-- Index--//
    // عرض جميع المستخدمين
    public function index()
    {
        $users = Cache::remember('users', 60, function () {
            return $this->database->getReference('users')->getValue() ?? [];
        });

        return view('users.index', compact('users'));
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

        //2. التحقق من البريد الإلكتروني بدون جلب جميع المستخدمين
        $existingUser = $this->database->getReference('users')
            ->orderByChild('email')
            ->equalTo($data['email'])
            ->getSnapshot()
            ->getValue();

        if ($existingUser) {
            return redirect()->back()->with('error', 'The email is already taken.');
        }

        //3. تشفير كلمة المرور
        $data['password'] = Hash::make($data['password']);

        //4. إضافة المستخدم إلى Firebase
        $this->database->getReference('users')->push([
            'firstName' => ucwords($data['firstName']),
            'lastName' => ucwords($data['lastName']),
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        //5. حذف الكاش لضمان تحديث البيانات
        Cache::forget('users');

        return redirect()->route('users')->with('msg', 'User added successfully.');
    }

    //-- Update--//
    // عرض نموذج تعديل مستخدم بدون إعادة استعلام من Firebase
    public function update($key)
    {
        $users = Cache::get('users', []);
        $user = $users[$key] ?? null;

        if (!$user) {
            return redirect()->route('users')->with('error', 'User not found.');
        }

        return view('users.update', compact('user', 'key'));
    }

    // تعديل المستخدم
    public function edit(Request $request, $id)
    {
        //1. التحقق من البيانات المستلمة
        $data = $request->validate([
            'firstName' => 'required|regex:/^[\p{Arabic}a-zA-Z\s]+$/u|min:3|max:20',
            'lastName' => 'required|regex:/^[\p{Arabic}a-zA-Z\s]+$/u|min:3|max:20',
        ]);

        $userRef = $this->database->getReference("users/{$id}");

        //2. التحقق من وجود المستخدم بدون تحميل البيانات
        if (!$userRef->getSnapshot()->exists()) {
            return redirect()->route('users')->with('error', 'User not found.');
        }

        //3. تعديل المستخدم
        $userRef->update([
            'firstName' => ucwords($data['firstName']),
            'lastName' => ucwords($data['lastName']),
        ]);

        //4. حذف الكاش لضمان تحديث البيانات
        Cache::forget('users');

        return redirect()->route('users')->with('msg', 'User updated successfully.');
    }

    //-- Delete--//
    // حذف مستخدم
    public function delete($id)
    {
        $userRef = $this->database->getReference("users/{$id}");

        //1. التحقق من وجود المستخدم باستخدام exists()
        if (!$userRef->getSnapshot()->exists()) {
            return redirect()->route('users')->with('error', 'User not found.');
        }

        //2. حذف المستخدم
        $userRef->remove();

        //3. حذف الكاش لضمان تحديث البيانات
        Cache::forget('users');

        return redirect()->route('users')->with('msg', 'User deleted successfully.');
    }
}
