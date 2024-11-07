<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**----------INDEX----------
     * Display a listing of events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get the 'filter' parameter from the request body (default is 'all').
        $filter = $request->input('filter', 'all');

        // Retrieve events based on the filter.
        if ($filter === 'past') {
            // If the filter is 'past', get events with a date earlier than today.
            $events = Event::where('date', '<', now())->get();
        } elseif ($filter === 'upcoming') {
            // If the filter is 'upcoming', get events with a date equal to or later than today.
            $events = Event::where('date', '>=', now())->get();
        } else {
            // If the filter is 'all' or any other value, get all events.
            $events = Event::all();
        }

        // Check if events were found.
        if ($events->isEmpty()) {
            // If no events are found, return a 404 response with a custom message.
            return response()->json([
                'status' => false,
                'message' => 'No events found.',
                'data' => []
            ], 404);
        }

        // Get the language parameter from the request, default is 'es'.
        $language = $request->input('lang', 'es');

        // If events are found, return a 200 response with the event data.
        return response()->json([
            'status' => true,
            'message' => 'Events retrieved successfully.',
            'data' => $events->map(function ($event) use ($language) {
                // Return event data, including language-specific fields.
                return [
                    'id' => $event->id,
                    'title' => $event->{'title_' . $language},
                    'description' => $event->{'description_' . $language},
                    'price' => $event->price,
                    'date' => $event->date,
                    'time' => $event->time,
                    'address' => $event->address,
                    'lat' => $event->lat,
                    'long' => $event->long,
                    'img_path' => $event->img_path // Use only img_path
                ];
            })
        ], 200);
    }

    /**----------INDEX DASH----------
     * Display a listing of events with filtering options (past, upcoming).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexDash(Request $request)
    {
        // Get the 'filter' parameter from the request (default is 'all').
        $filter = $request->input('filter', 'all');

        // Retrieve events based on the filter.
        if ($filter === 'past') {
            // If the filter is 'past', get events with a date earlier than today.
            $events = Event::where('date', '<', now())->get();
        } elseif ($filter === 'upcoming') {
            // If the filter is 'upcoming', get events with a date equal to or later than today.
            $events = Event::where('date', '>=', now())->get();
        } else {
            // If the filter is 'all' or any other value, get all events.
            $events = Event::all();
        }

        // Check if events were found.
        if ($events->isEmpty()) {
            // If no events are found, return a 404 response with a custom message.
            return response()->json([
                'status' => false,
                'message' => 'No events found.',
                'data' => []
            ], 404);
        }

        // Return a 200 response with the event data.
        return response()->json([
            'status' => true,
            'message' => 'Events retrieved successfully.',
            'data' => $events
        ], 200);
    }

    /**
     * Store a newly created event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for input data.
        $rules = [
            'title_es' => 'required|string|max:256',
            'title_en' => 'required|string|max:256',
            'description_es' => 'required|string|max:2048',
            'description_en' => 'required|string|max:2048',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:256',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'img' => 'nullable|image|max:2048', // Updated validation rule
        ];

        // Validate the input data using the defined rules.
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return a 400 response with the validation errors.
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Extract valid data from request, excluding images.
        $data = $request->only(['title_es', 'title_en', 'description_es', 'description_en', 'price', 'date', 'time', 'address', 'lat', 'long']);

        // If an image was uploaded, store it and add the path to data array.
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $path = $image->store('events', 'public'); // Store image in the 'events' folder in public disk.
            $data['img_path'] = $path; // Add image path to data array.
        }

        // Create a new Event object with the provided data and save it to the database.
        $event = new Event($data);
        $event->save();

        // Return a JSON response indicating that the event was created successfully, along with event data.
        return response()->json([
            'status' => true,
            'message' => 'Event created successfully',
            'data' => $event,
        ], 200);
    }

    /**----------SHOW----------
     * Display the specified event.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request instance.
     * @param  int  $id  The ID of the event to retrieve.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        // Attempt to find the event by ID.
        $event = Event::find($id);

        // If the event is not found, return a 404 response with an error message.
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'No event found with the provided ID.'
            ], 404);
        }

        // Retrieve the 'lang' query parameter from the request, defaulting to 'es' if not present.
        $language = $request->query('lang', 'es');

        // Return a 200 response with the event data.
        return response()->json([
            'status' => true,
            'message' => 'Event retrieved successfully.',
            'data' => [
                'id' => $event->id,
                'title' => $event->{'title_' . $language},
                'description' => $event->{'description_' . $language},
                'price' => $event->price,
                'date' => $event->date,
                'time' => $event->time,
                'address' => $event->address,
                'lat' => $event->lat,
                'long' => $event->long,
                'img_path' => $event->img_path // Use only img_path
            ]
        ], 200);
    }

    // Method to update event data
    public function updateData(Request $request, $id)
    {
        // Define validation rules for event data.
        $rules = [
            'title_es' => 'required|string|max:256',
            'title_en' => 'required|string|max:256',
            'description_es' => 'required|string|max:2048',
            'description_en' => 'required|string|max:2048',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:256',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
        ];

        // Validate the request data.
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return a 400 response with validation errors.
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Find the event by ID.
        $event = Event::find($id);

        // If the event is not found, return a 404 response.
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'No event found with the provided ID.'
            ], 404);
        }

        // Update the event with the provided data.
        $event->update($request->all());

        // Return a 200 response indicating the event data was updated successfully.
        return response()->json([
            'status' => true,
            'message' => 'Event data updated successfully.',
            'data' => $event
        ], 200);
    }

    // Method to update event image
    public function updateImage(Request $request, $id)
    {
        // Define validation rules for the image.
        $rules = [
            'img' => 'required|image|max:2048', // Image is required and must be a valid image file.
        ];

        // Validate the request data.
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return a 400 response with validation errors.
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Find the event by ID.
        $event = Event::find($id);

        // If the event is not found, return a 404 response.
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'No event found with the provided ID.'
            ], 404);
        }

        // If the event has a previous image, delete it.
        if ($event->getRawOriginal('img_path')) {
            Storage::disk('public')->delete($event->getRawOriginal('img_path'));
        }

        // Store the new image and update the event's image path.
        $image = $request->file('img');
        $path = $image->store('events', 'public');
        $event->img_path = $path;
        $event->save();

        // Return a 200 response indicating the image was updated successfully.
        return response()->json([
            'status' => true,
            'message' => 'Event image updated successfully.',
            'data' => $event,
        ], 200);
    }

    /**----------DESTROY----------
     * Remove the specified event from storage.
     *
     * @param  int  $id  The ID of the event to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the event by ID.
        $event = Event::find($id);

        // If the event is not found, return a 404 response.
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'No event found with the provided ID.'
            ], 404);
        }

        // If the event has an image, delete it from storage.
        if ($event->getRawOriginal('img_path')) {
            Storage::disk('public')->delete($event->getRawOriginal('img_path'));
        }

        // Delete the event from the database.
        $event->delete();

        // Return a 200 response indicating the event was deleted successfully.
        return response()->json([
            'status' => true,
            'message' => 'Event deleted successfully.',
        ], 200);
    }
}
