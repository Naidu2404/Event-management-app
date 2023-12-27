<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    use CanLoadRelationships;

    //we need to add middleware 'auth:sanctum' to add authentication requirement to routes
    public function __construct(){
        $this->middleware("auth:sanctum")->except(['index','show']);
        $this->authorizeResource(Event::class,'event');
    }

    private array $relations = ['user','attendees','attendees.user'];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //define an array of accepted relations so that we do not validate any unknown relations
        

        //we need to checck which relations are given in the query
        //create a query object
        $query = $this->loadRelationships(Event::query());


        

        return EventResource::collection($query->latest()->paginate());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validating the event from request object
        $data = $request->validate([
            "name"=> "required|string|max:255",
            'description' => 'nullable|string',
            'start_date'=> 'required|date',
            'end_date'=> 'required|date|after:start_date',
        ]);

        $event = Event::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //we need to use the gate we created to check whether thwe user can update this specific event or not
        // if(Gate::denies('update-event', $event)) {
        //     abort(403,'You are not authorized to update this event');
        // }

        //we can replace the above code for using the gate as follows
        // $this->authorize('update-event', $event);


        //here in validate the sometimes checks for validation only sometimes and does not each and every time
        $event->update(
            $request->validate([
                "name"=> "sometimes|string|max:255",
                'description' => 'nullable|string',
                'start_date'=> 'sometimes|date',
                'end_date'=> 'sometimes|date|after:start_date',
            ])
        );

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //we use the delete mwthod on the event
        $event->delete();

        return response(status:204);
    }
}
