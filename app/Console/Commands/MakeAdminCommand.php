<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a locally registered user to an administrator by their email.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found: {$email}. Please verify the email is registered.");
            return;
        }

        $user->update(['role' => 'admin']);
        $this->info("Success! User {$user->email} has been promoted to Admin.");
    }
}
