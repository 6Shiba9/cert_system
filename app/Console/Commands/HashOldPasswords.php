<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HashOldPasswords extends Command
{
    protected $signature = 'users:hash-passwords';
    protected $description = 'Convert old plain text passwords to hashed passwords';

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (strlen($user->password) < 60) { // ตรวจสอบว่ารหัสผ่านยังไม่ hash
                $user->password = Hash::make($user->password);
                $user->save();
                $this->info("Password for user {$user->email} hashed successfully.");
            }
        }

        $this->info('All old passwords have been hashed.');
    }
}
