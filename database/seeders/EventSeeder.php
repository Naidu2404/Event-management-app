<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //we are getting all the users
        $users = User::all();

        //we are creating 200 records of events
        for( $i = 0; $i < 200; $i++ ) {
            //we are getting a random user from all the users available
            $user = $users->random();

            //now we are using the factory to create a record with the userid of the user
            \App\Models\Event::factory()->create([
                'user_id' => $user->id
            ]);
        }
    }
}
