<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Community;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Attendance;
use App\Models\Certificate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $memberRole = Role::firstOrCreate(['name' => 'member']);

        // 2. Create Specific Admin/Owner Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@peta.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin'),
            ]
        );
        // Ensure user has owner role
        if (!$admin->hasRole('owner')) {
            $admin->assignRole($ownerRole);
        }

        // 3. Create Regular Users
        $users = User::factory(10)->create();
        foreach ($users as $user) {
            $user->assignRole($memberRole);
        }

        // 4. Create Communities
        $communities = Community::factory(3)->create();

        // 5. Assign Members to Communities & Create Events
        foreach ($communities as $community) {
            // Assign admin as admin of all communities
            $community->members()->create([
                'user_id' => $admin->id,
                'role' => 'admin'
            ]);

            // Assign random users as members
            $randomUsers = $users->random(5);
            foreach ($randomUsers as $user) {
                $community->members()->create([
                    'user_id' => $user->id,
                    'role' => 'member'
                ]);
            }

            // Create Events for Community
            $events = Event::factory(3)->create([
                'community_id' => $community->id,
                'status' => 'Published'
            ]);

            // For each Event, create participants
            foreach ($events as $event) {
                // Random users participate
                $participants = $users->random(4);
                
                foreach ($participants as $user) {
                    $participant = EventParticipant::factory()->create([
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'status' => 'Attended'
                    ]);

                    // Generate Attendance
                    Attendance::factory()->create([
                        'event_participant_id' => $participant->id,
                    ]);

                    // Generate Certificate
                    Certificate::factory()->create([
                        'event_participant_id' => $participant->id,
                    ]);
                }
            }
        }

        // 6. Create Activity Logs
        \App\Models\ActivityLog::factory(10)->create([
            'user_id' => $admin->id
        ]);
    }
}
