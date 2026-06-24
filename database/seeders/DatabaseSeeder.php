<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Privilege;
use App\Models\Right;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin User',
            'email' => 'support@fig.limited',
            'email_verified_at' => now(),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            'is_admin' => true
        ]);
        $user = User::create([
            'name' => 'Editor',
            'email' => 'manager@woodvalecentre.co.uk',
            'email_verified_at' => now(),
            'password' => Hash::make(env('EDITOR_PASSWORD', 'password')),
        ]);

        $editorRole = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
        ]);

        Privilege::create([
            'role_id' => $editorRole->id,
            'user_id' => $user->id,
        ]);

        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.posts.index',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.posts.store',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.posts.show',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.posts.update',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.posts.destroy',
        ]);

        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.pages.index',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.pages.store',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.pages.show',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.pages.update',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.pages.destroy',
        ]);

        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.menu-items.index',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.menu-items.store',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.menu-items.show',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.menu-items.update',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.menu-items.destroy',
        ]);

        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.media.index',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.media.store',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.media.show',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.media.update',
        ]);
        Right::create([
            'role_id' => $editorRole->id,
            'controller_method_name' => 'internal.media.destroy',
        ]);

        $this->call([
            MenuItemSeeder::class,
            StatSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
