<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();

        // If no services are found, return a 404 response.
        if ($contacts->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No contacts found.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Contacts retrieved successfully',
            'data' => $contacts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:13',
            'email' => 'required|email|max:256',
            'address' => 'required|string|max:512',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        $contact = Contact::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Contact created successfully',
            'data' => $contact,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'status' => false,
                'message' => 'No contact found with the provided ID.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $contact,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'phone' => 'string|max:13',
            'email' => 'email|max:256',
            'address' => 'string|max:512',
            'lat' => 'numeric',
            'long' => 'numeric',
        ]);

        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'status' => false,
                'message' => 'No contact found with the provided ID.'
            ], 404);
        }

        $contact->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Contact updated successfully',
            'data' => $contact,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'status' => false,
                'message' => 'No contact found with the provided ID.'
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'status' => true,
            'message' => 'Contact deleted successfully',
        ], 200);
    }
}
