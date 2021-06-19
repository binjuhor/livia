<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name'              => 'Son Do',
            'email'             => 'son@chillbits.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('sonbanhtrai')
        ]);

        $team = $user->ownedTeams()->create([
            'name'          => 'Crazyknot',
            'personal_team' => true
        ]);

        $team->users()->attach($user, ['role' => 'admin']);
        $user->update([
            'current_team_id' => $team->id
        ]);
    }
}
