<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $viewer = Role::firstOrCreate(['name' => 'viewer'], ['display_name' => 'Viewer']);
        $editor = Role::firstOrCreate(['name' => 'editor'], ['display_name' => 'Editor']);
        $admin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);

        $viewerUser = User::firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$viewerUser->roles()->where('roles.id', $viewer->id)->exists()) {
            $viewerUser->roles()->attach($viewer);
        }

        $editorUser = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$editorUser->roles()->where('roles.id', $editor->id)->exists()) {
            $editorUser->roles()->attach($editor);
        }

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$adminUser->roles()->where('roles.id', $admin->id)->exists()) {
            $adminUser->roles()->attach($admin);
        }

        $this->command->info('Usuários e roles criados.');
    }
}
