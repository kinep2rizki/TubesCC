<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-dummy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = base_path('dummy_data.json');
        if (!file_exists($file)) {
            $this->error("File dummy_data.json not found.");
            return;
        }

        $this->info("Loading dummy_data.json...");
        $data = json_decode(file_get_contents($file), true);

        // 1. Users
        if (isset($data['users'])) {
            $this->info("Importing Users...");
            foreach ($data['users'] as $row) {
                \App\Models\User::updateOrCreate(
                    ['id' => $row['id']],
                    [
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'password' => bcrypt('password'),
                    ]
                );
            }
        }

        // 2. Communities
        // Ensure community 3 exists, or we get foreign key errors. The excel has community_id and community_name in `events`.
        if (isset($data['events'])) {
            foreach ($data['events'] as $row) {
                if (isset($row['community_id']) && isset($row['community_name'])) {
                    \App\Models\Community::updateOrCreate(
                        ['id' => $row['community_id']],
                        [
                            'name' => $row['community_name'],
                            'description' => 'Dummy community',
                        ]
                    );
                }
            }
        }

        // 3. Community Members
        if (isset($data['community_members'])) {
            $this->info("Importing Community Members...");
            foreach ($data['community_members'] as $row) {
                \App\Models\CommunityMember::updateOrCreate(
                    [
                        'community_id' => $row['community_id'],
                        'user_id' => $row['user_id']
                    ],
                    [
                        'role' => $row['role'] ?? 'Member',
                    ]
                );
            }
        }

        // 4. Events
        if (isset($data['events'])) {
            $this->info("Importing Events...");
            foreach ($data['events'] as $row) {
                $date = $this->parseExcelDate($row['date'] ?? null) ?: now();
                \App\Models\Event::updateOrCreate(
                    ['id' => $row['event_id']],
                    [
                        'community_id' => $row['community_id'],
                        'title' => $row['title'],
                        'description' => 'Event description',
                        'start_date' => $date,
                        'end_date' => clone $date->addHours(2),
                        'location' => 'Virtual',
                        'capacity' => 1000,
                        'status' => 'Past',
                    ]
                );
            }
        }

        // 5. Event Participants
        if (isset($data['event_participants'])) {
            $this->info("Importing Event Participants...");
            foreach ($data['event_participants'] as $row) {
                \App\Models\EventParticipant::updateOrCreate(
                    ['id' => $row['participant_id']],
                    [
                        'event_id' => $row['event_id'],
                        'user_id' => $row['user_id'],
                        'status' => $row['status'] ?? 'Registered',
                    ]
                );
            }
        }

        // 6. Attendance
        if (isset($data['attendance'])) {
            $this->info("Importing Attendance...");
            foreach ($data['attendance'] as $row) {
                \App\Models\Attendance::updateOrCreate(
                    ['id' => $row['attendance_id']],
                    [
                        'event_participant_id' => $row['participant_id'],
                        'check_in_time' => $this->parseExcelDate($row['check_in_time'] ?? null) ?: now(),
                        'method' => $row['method'] ?? 'Manual',
                    ]
                );
            }
        }

        // 7. Activity Logs
        if (isset($data['activity_logs'])) {
            $this->info("Importing Activity Logs...");
            foreach ($data['activity_logs'] as $row) {
                \App\Models\ActivityLog::updateOrCreate(
                    ['id' => $row['log_id']],
                    [
                        'community_id' => $row['community_id'] ?? null,
                        'user_id' => 1, // Fallback
                        'action' => $row['activity'],
                        'description' => $row['activity'] . ' was performed.',
                        'created_at' => $this->parseExcelDate($row['created_at'] ?? null) ?: now(),
                    ]
                );
            }
        }

        $this->info("Data import completed successfully!");
    }

    private function parseExcelDate($excelDate) {
        if (!$excelDate) return null;
        if (is_numeric($excelDate)) {
            // Excel dates start from Jan 1 1900 (actually 1899-12-30 due to 1900 leap year bug in Excel)
            return \Carbon\Carbon::create(1899, 12, 30)->addDays((float)$excelDate);
        }
        return \Carbon\Carbon::parse($excelDate);
    }
}
