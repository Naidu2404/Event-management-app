<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //we get all the users and the events
        $users = User::all();
        $events = Event::all();

        //for every user we add a random of 1 to 3 events per user
        foreach($users as $user) {
            //here the inner rand gives a random number from 1 to 3
            //the outer random function generates those many random events
            $eventsToAttend = $events->random(rand(1,3));

            foreach($eventsToAttend as $event) {
                \App\Models\Attendee::create([
                    "user_id"=> $user->id,
                    "event_id"=> $event->id,
                ]);
            }
        }
    }
}
