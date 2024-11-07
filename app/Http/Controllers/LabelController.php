<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabelController extends Controller
{
    /**----------INDEX----------
     * Display a listing of labels.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $labels = Label::all();

        if ($labels->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No labels found.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Labels retrieved successfully.',
            'data' => $labels
        ], 200);
    }

    /**----------STORE----------
     * Store a newly created label in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:512',
        ]);

        $label = Label::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Label created successfully.',
            'data' => $label,
        ], 200);
    }

    /**----------SHOW----------
     * Display the specified label.
     *
     * @param  Label  $label
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Attempt to find the label by ID.
        $label = Label::find($id);

        // If the label is not found, return a 404 response with an error message.
        if (!$label) {
            return response()->json([
                'status' => false,
                'message' => 'No label found with the provided ID.'
            ], 404);
        }

        // Check if label is for video and update the text with the asset URL.
        if ($label->id == 2 && $label->text) {
            $label->text = asset('storage/' . $label->text);
        }

        return response()->json([
            'status' => true,
            'message' => 'Label retrieved successfully.',
            'data' => $label
        ], 200);
    }

    /**----------UPDATE DATA----------
     * Update the specified label in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Label  $label
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Attempt to find the label by ID.
        $label = Label::find($id);

        // If the label is not found, return a 404 response with an error message.
        if (!$label) {
            return response()->json([
                'status' => false,
                'message' => 'No label found with the provided ID.'
            ], 404);
        }

        $request->validate([
            'text' => 'required|string|max:512',
        ]);

        $label->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Label updated successfully.',
        ], 200);
    }

    /**----------UPDATE VIDEO LABEL----------
     * Update the video label with ID 2.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'nullable|mimetypes:video/mp4,video/x-msvideo,video/x-matroska,video/quicktime,video/mpeg,video/3gpp,video/x-ms-wmv|max:819200', // 100 MB max
        ]);

        // Find the label with ID 2.
        $label = Label::find(2);

        if (!$label) {
            return response()->json([
                'status' => false,
                'message' => 'No video label found.'
            ], 404);
        }

        // If a new video is uploaded, handle the upload process.
        if ($request->hasFile('video')) {
            // Delete the old video if it exists.
            if ($label->text) {
                Storage::disk('public')->delete($label->text);
            }

            // Store the new video and update the label with the new path.
            $video = $request->file('video');
            $path = $video->store('videos', 'public');
            $label->text = $path;
            $label->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Video label updated successfully.',
            'data' => $label
        ], 200);
    }

    /**----------DESTROY----------
     * Remove the specified label from storage.
     *
     * @param  Label  $label
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Attempt to find the label by ID.
        $label = Label::find($id);

        // If the label is not found, return a 404 response with an error message.
        if (!$label) {
            return response()->json([
                'status' => false,
                'message' => 'No label found with the provided ID.'
            ], 404);
        }

        // If the label has a video, delete it from storage.
        if ($label->id == 2 && $label->text) {
            Storage::disk('public')->delete($label->text);
        }

        $label->delete();

        return response()->json([
            'status' => true,
            'message' => 'Label deleted successfully.',
        ], 200);
    }
}
