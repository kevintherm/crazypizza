<?php

namespace App\Console\Commands;

use App\Models\User;
use Auth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user info';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();

        $email = $this->ask('Email of the user to update');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            DB::rollBack();
            return;
        }

        $this->info("Leave blank to keep current value");

        $newEmail = $this->ask('New email', $user->email);
        $name = $this->ask('New name', $user->name);
        $password = $this->secret('New password');
        $role = $this->choice('New role', array_values(User::ROLE), $user->role);

        $user->name = $name;
        $user->email = $newEmail;
        // $user->role = $role;

        if ($password) {
            $user->password = Hash::make($password);
        }

        $user->save();

        DB::table('sessions')->where('user_id', $user->id)->delete();

        $this->info("User {$user->name} updated successfully.");

        DB::commit();
    }
}
