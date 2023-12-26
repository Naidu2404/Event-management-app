<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    //we can only access user in the attendee
    //so we add the requests array only a single value of user
    private array $relations = ['user'];


    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        //we are getting all the attendees of the specified event as attendees exist only based on an event
        $attendees = $this->loadRelationships($event->attendees()->latest());
        return AttendeeResource::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        //
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 1
            ])
         );

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event,Attendee $attendee)
    {
        //
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $event,Attendee $attendee)
    {
        //
        $attendee->delete();

        return response(status:204);
    }
}