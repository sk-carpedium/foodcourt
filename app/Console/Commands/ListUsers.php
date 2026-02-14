<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    protected $signature = 'users:list';
    protected $description = 'List all users with their roles';

    public function handle()
    {
        $users = User::with('roles')->get();
        
        $this->info('All Users:');
        $this->line('');
        
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->join(', ') ?: 'No roles';
            $this->line("📧 {$user->email} - {$user->name} ({$roles})");
        }
        
        $this->line('');
        $this->info("Total users: {$users->count()}");
        
        return 0;
    }
}