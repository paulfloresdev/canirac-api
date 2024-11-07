<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of social media records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all social media records
        $socialMedias = SocialMedia::all();

        // Check if any social media records were found
        if ($socialMedias->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No social media records found.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Social medias retrieved successfully.',
            'data' => $socialMedias,
        ], 200);
    }

    /**
     * Store a newly created social media record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation rules for storing a new social media record
        $rules = [
            'type' => 'required|string|max:24',
            'label' => 'required|string|max:128',
            'url' => 'required|string|max:512',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        // Create a new social media record
        $socialMedia = SocialMedia::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'SocialMedia created successfully.',
            'data' => $socialMedia,
        ], 201);
    }

    /**
     * Display the specified social media record.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the social media record by ID
        $socialMedia = SocialMedia::find($id);

        // Check if the record was found
        if (!$socialMedia) {
            return response()->json([
                'status' => false,
                'message' => 'No social media found with the provided ID.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Social media retrieved successfully.',
            'data' => $socialMedia,
        ], 200);
    }

    /**
     * Update the specified social media record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find the social media record by ID
        $socialMedia = SocialMedia::find($id);

        // Check if the record was found
        if (!$socialMedia) {
            return response()->json([
                'status' => false,
                'message' => 'No social media found with the provided ID.',
            ], 404);
        }

        // Validation rules for updating a social media record
        $rules = [
            'type' => 'sometimes|required|string|max:24',
            'label' => 'sometimes|required|string|max:128',
            'url' => 'sometimes|required|string|max:512',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        // Update the social media record with provided data
        $socialMedia->update($request->only(['type', 'label', 'url']));

        return response()->json([
            'status' => true,
            'message' => 'SocialMedia updated successfully.',
            'data' => $socialMedia,
        ], 200);
    }

    /**
     * Remove the specified social media record from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the social media record by ID
        $socialMedia = SocialMedia::find($id);

        // Check if the record was found
        if (!$socialMedia) {
            return response()->json([
                'status' => false,
                'message' => 'No social media found with the provided ID.',
            ], 404);
        }

        // Delete the social media record
        $socialMedia->delete();

        return response()->json([
            'status' => true,
            'message' => 'SocialMedia deleted successfully.',
        ], 200);
    }
}
