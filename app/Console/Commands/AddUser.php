<?php

namespace App\Console\Commands;

use App\Models\User;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();

        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password');
        $role = $this->choice('Role', User::ROLE);

        User::create([
            'name' => $name,
            'email' => $email,
            // 'role' => $role,
            'password' => Hash::make($password),
        ]);

        $this->info("User {$name} created successfully.");

        DB::commit();
    }
}
