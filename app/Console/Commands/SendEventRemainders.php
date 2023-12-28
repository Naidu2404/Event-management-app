<?php

namespace App\Console\Commands;

use App\Notifications\EventRemainderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventRemainders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-remainders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all the attendees that the event is near';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //we need to get all the events with their attendees and user which are going to start in the next 24 hrs
        $events = \App\Models\Event::with('attendees.user')
                    ->whereBetween('start_date',[now(),now()->addDay()])
                    ->get();

        //lets count the events
        $eventCount = $events->count();

        //lets set the Str::plural
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("we have got {$eventCount} {$eventLabel}");

        foreach ($events as $event) {
            $attendees = $event->attendees;

            foreach ($attendees as $attendee) {
                $attendee->user->notify(
                    new EventRemainderNotification($event)
                );
            }
        }

        //here goes what this command do
        $this->info('Remainder notifications sent successfully');
    }
}
