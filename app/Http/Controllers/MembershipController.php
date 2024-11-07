<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    /**----------INDEX----------
     * Display a listing of memberships.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Retrieve all memberships from the database.
        $memberships = Membership::all();

        // Check if any memberships were found.
        if ($memberships->isEmpty()) {
            // If no memberships are found, return a 404 response with a custom message.
            return response()->json([
                'status' => false,
                'message' => 'No memberships found.',
                'data' => []
            ], 404);
        }

        // If memberships are found, return a 200 response with the memberships data.
        return response()->json([
            'status' => true,
            'message' => 'Memberships retrieved successfully.',
            'data' => $memberships->map(function ($membership) use ($request) {
                // Get the language parameter from the request, default to 'es'.
                $language = $request->query('lang', 'es');

                // Return the membership data, including language-specific fields.
                return [
                    'id' => $membership->id,
                    'size' => $membership->{'size_' . $language},
                    'description' => $membership->{'description_' . $language},
                    'price1' => $membership->price1,
                    'price2' => $membership->price2,
                    'price3' => $membership->price3
                ];
            })
        ], 200);
    }

    /**----------INDEX DASH----------
     * Display a listing of memberships without language filtering.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexDash()
    {
        // Retrieve all memberships from the database.
        $memberships = Membership::all();

        // Check if any memberships were found.
        if ($memberships->isEmpty()) {
            // If no memberships are found, return a 404 response with a custom message.
            return response()->json([
                'status' => false,
                'message' => 'No memberships found.',
                'data' => []
            ], 404);
        }

        // If memberships are found, return a 200 response with the memberships data.
        return response()->json([
            'status' => true,
            'message' => 'Memberships retrieved successfully.',
            'data' => $memberships // No need to map, return the data as is.
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'size_es' => 'required|string|max:255',
            'size_en' => 'required|string|max:255',
            'description_es' => 'required|string|max:255',
            'description_en' => 'required|string|max:255',
            'price1' => 'required|numeric',
            'price2' => 'required|numeric',
            'price3' => 'required|numeric',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $membership = new Membership($request->input());
        $membership->save();
        return response()->json([
            'status' => true,
            'message' => 'Membership created successfully.'
        ], 200);
    }

    /**----------SHOW----------
     * Display the specified membership.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request instance.
     * @param  int  $id  The ID of the membership to retrieve.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        // Attempt to find the membership by ID.
        // If the membership is not found, return a 404 response with an error message.
        $membership = Membership::find($id);

        if (!$membership) {
            return response()->json([
                'status' => false,
                'message' => 'No membership found with the provided ID.'
            ], 404);
        }

        // Retrieve the 'lang' query parameter from the request, defaulting to 'es' if not present.
        $language = $request->query('lang', 'es');

        // Return a 200 response with the membership data.
        // Concatenate 'size_' and 'description_' with the language to get the localized values.
        return response()->json([
            'status' => true,
            'message' => 'Membership retrieved successfully.',
            'data' => [
                'id' => $membership->id,
                'size' => $membership->{'size_' . $language},
                'description' => $membership->{'description_' . $language},
                'price1' => $membership->price1,
                'price2' => $membership->price2,
                'price3' => $membership->price3
            ],
        ], 200);
    }


    /**
     * Update the specified membership in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  The ID of the membership to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Define validation rules for the request data.
        $rules = [
            'size_es' => 'required|string|max:255',
            'size_en' => 'required|string|max:255',
            'description_es' => 'required|string|max:255',
            'description_en' => 'required|string|max:255',
            'price1' => 'required|numeric',
            'price2' => 'required|numeric',
            'price3' => 'required|numeric',
        ];

        // Validate the incoming request data against the defined rules.
        $validator = Validator::make($request->input(), $rules);

        // If validation fails, return a 400 response with the validation errors.
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Attempt to find the membership by its ID.
        // If the membership is not found, return a 404 response with an error message.
        $membership = Membership::find($id);

        if (!$membership) {
            return response()->json([
                'status' => false,
                'message' => 'No membership found with the provided ID.'
            ], 404);
        }

        // Update the membership with the validated data from the request.
        $membership->update($request->input());

        // Return a 200 response indicating that the membership was updated successfully.
        return response()->json([
            'status' => true,
            'message' => 'Membership updated successfully.'
        ], 200);
    }


    /**
     * Remove the specified membership from storage.
     *
     * @param  int  $id  The ID of the membership to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Attempt to find the membership by ID.
        // If the membership is not found, return a 404 response with an error message.
        $membership = Membership::find($id);

        if (!$membership) {
            return response()->json([
                'status' => false,
                'message' => 'No membership found with the provided ID.'
            ], 404);
        }

        // Delete the membership record from the database.
        $membership->delete();

        // Return a 200 response indicating that the membership was deleted successfully.
        return response()->json([
            'status' => true,
            'message' => 'Membership deleted successfully.'
        ], 200);
    }
}
