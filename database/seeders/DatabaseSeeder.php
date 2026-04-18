<?php

namespace Database\Seeders;

use App\Models\MembershipStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@benchzfitness.local'],
            [
                'name' => 'Benchz Admin',
                'role' => 'admin',
                'profile_picture' => 'profile_picture/default-profile.png',
                'password' => Hash::make('Admin12345!'),
            ]
        );

        $member = User::updateOrCreate(
            ['email' => 'member@benchzfitness.local'],
            [
                'name' => 'Demo Member',
                'role' => 'member',
                'profile_picture' => 'profile_picture/default-profile.png',
                'password' => Hash::make('Member12345!'),
            ]
        );

        MembershipStatus::updateOrCreate(
            ['user_id' => $member->id],
            [
                'planType' => 'None',
                'status' => 'Inactive',
                'expiry_date' => null,
            ]
        );
    }
}
