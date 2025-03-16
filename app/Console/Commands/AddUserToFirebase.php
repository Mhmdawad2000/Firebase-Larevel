<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Cache;
use Faker\Factory as Faker;

class AddUserToFirebase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:add-user {count=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds users to Firebase Realtime Database';

    protected $database;

    /**
     * Execute the console command.
     */
    public function handle(Database $database)
    {
        $this->database = $database;
        $faker = Faker::create();
        $count = (int) $this->argument('count'); // عدد المستخدمين المطلوب إضافتهم

        // جلب جميع الإيميلات الحالية لمنع التكرار
        $existingEmails = Cache::remember('users_emails', 60, function () {
            $users = $this->database->getReference('users')->getValue();
            return array_column($users ?? [], 'email');
        });

        $addedUsers = 0;

        for ($i = 0; $i < $count; $i++) {
            $email = $faker->unique()->safeEmail();

            // التحقق من أن البريد الإلكتروني غير مكرر
            if (in_array($email, $existingEmails)) {
                continue;
            }

            $user = [
                'firstName' => $faker->firstName(),
                'lastName' => $faker->lastName(),
                'email' => $email,
                'password' => Hash::make('password123'),
            ];

            // إضافة المستخدم إلى Firebase
            $this->database->getReference('users')->push($user);
            $addedUsers++;
        }

        // تحديث الكاش بعد إضافة المستخدمين
        Cache::forget('users');
        Cache::forget('users_emails');

        $this->info("Successfully added {$addedUsers} user(s) to Firebase!");
    }
}
