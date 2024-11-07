<?php

namespace App\Http\Controllers;

use App\Models\ChamberMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ChamberMemberController extends Controller
{
    /**
     * Display a listing of chamber members.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get the language parameter from the request, default is 'es'.
        $language = $request->input('lang', 'es');

        // Retrieve all chamber members.
        $chamberMembers = ChamberMember::all();

        // Check if chamber members were found.
        if ($chamberMembers->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No chamber members found.',
                'data' => []
            ], 404);
        }

        // Return a 200 response with the chamber members data.
        return response()->json([
            'status' => true,
            'message' => 'Chamber members retrieved successfully.',
            'data' => $chamberMembers->map(function ($member) use ($language) {
                // Split the name by spaces to extract initials
                $nameParts = explode(' ', $member->name);
                $initials = strtoupper(substr($nameParts[0], 0, 1)); // First initial

                if (count($nameParts) > 1) {
                    // Add initial of the second name or first surname
                    $initials .= strtoupper(substr($nameParts[1], 0, 1));
                }

                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'role' => $language === 'en' ? $member->role_en : $member->role_es,
                    'img_path' => $member->img_path,
                    'initials' => $initials, // Added initials field
                ];
            })
        ], 200);
    }


    /**----------INDEX DASH----------
     * Display a listing of chamber members without language filtering.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexDash()
    {
        // Retrieve all chamber members from the database.
        $members = ChamberMember::all();

        // Check if any members were found.
        if ($members->isEmpty()) {
            // If no members are found, return a 404 response with a custom message.
            return response()->json([
                'status' => false,
                'message' => 'No chamber members found.',
                'data' => []
            ], 404);
        }

        // If members are found, return a 200 response with the members data and initials.
        return response()->json([
            'status' => true,
            'message' => 'Chamber members retrieved successfully.',
            'data' => $members->map(function ($member) {
                // Split the name by spaces to extract initials.
                $nameParts = explode(' ', $member->name);
                $initials = strtoupper(substr($nameParts[0], 0, 1)); // First initial.

                if (count($nameParts) > 1) {
                    // Add initial of the second name or first surname.
                    $initials .= strtoupper(substr($nameParts[1], 0, 1));
                }

                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'role_es' => $member->role_es, // Return role_es.
                    'role_en' => $member->role_en, // Return role_en.
                    'img_path' => $member->img_path,
                    'initials' => $initials, // Added initials field.
                ];
            })
        ], 200);
    }


    /**
     * Store a newly created chamber member in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for input data.
        $rules = [
            'name' => 'required|string|max:128',
            'role_es' => 'required|string|max:128',
            'role_en' => 'required|string|max:128',
            'img' => 'nullable|image|max:2048', // Optional image field
        ];

        // Validate the input data using the defined rules.
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return a 400 response with validation errors.
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Store the image if it is provided.
        $imgPath = null;
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imgPath = $image->store('chamber_members', 'public'); // Store image in the 'chamber_members' folder in public disk.
        }

        // Create a new chamber member with the provided data, including the image path if available.
        $chamberMember = ChamberMember::create([
            'name' => $request->input('name'),
            'role_es' => $request->input('role_es'),
            'role_en' => $request->input('role_en'),
            'img_path' => $imgPath,
        ]);

        // Return a JSON response indicating the chamber member was created successfully.
        return response()->json([
            'status' => true,
            'message' => 'Chamber member created successfully.',
            'data' => $chamberMember,
        ], 201);
    }


    /**
     * Display the specified chamber member.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        // Attempt to find the chamber member by ID.
        $chamberMember = ChamberMember::find($id);

        // If the chamber member is not found, return a 404 response with an error message.
        if (!$chamberMember) {
            return response()->json([
                'status' => false,
                'message' => 'No chamber member found with the provided ID.'
            ], 404);
        }

        // Retrieve the 'lang' query parameter from the request, defaulting to 'es' if not present.
        $language = $request->query('lang', 'es');

        // Return a 200 response with the chamber member data.
        return response()->json([
            'status' => true,
            'message' => 'Chamber member retrieved successfully.',
            'data' => [
                'id' => $chamberMember->id,
                'name' => $chamberMember->name,
                'role' => $language === 'en' ? $chamberMember->role_en : $chamberMember->role_es,
                'img_path' => $chamberMember->img_path
            ]
        ], 200);
    }

    /**
     * Update the specified chamber member data in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateData(Request $request, $id)
    {
        // Find the chamber member by ID.
        $chamberMember = ChamberMember::find($id);

        // If the chamber member is not found, return a 404 response.
        if (!$chamberMember) {
            return response()->json([
                'status' => false,
                'message' => 'No chamber member found with the provided ID.'
            ], 404);
        }

        // Define validation rules for chamber member data.
        $rules = [
            'name' => 'sometimes|required|string|max:128',
            'role_es' => 'sometimes|required|string|max:128',
            'role_en' => 'sometimes|required|string|max:128',
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

        // Update the chamber member with the provided data.
        $chamberMember->update($request->only(['name', 'role_es', 'role_en']));

        // Return a 200 response indicating the chamber member data was updated successfully.
        return response()->json([
            'status' => true,
            'message' => 'Chamber member data updated successfully.',
            'data' => $chamberMember
        ], 200);
    }

    /**
     * Update the specified chamber member image in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
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

        // Find the chamber member by ID.
        $chamberMember = ChamberMember::find($id);

        // If the chamber member is not found, return a 404 response.
        if (!$chamberMember) {
            return response()->json([
                'status' => false,
                'message' => 'No chamber member found with the provided ID.'
            ], 404);
        }

        // If the chamber member has a previous image, delete it.
        if ($chamberMember->getRawOriginal('img_path')) {
            Storage::disk('public')->delete($chamberMember->getRawOriginal('img_path'));
        }

        // Store the new image and update the chamber member's image path.
        $image = $request->file('img');
        $path = $image->store('chamber_members', 'public'); // Store image in the 'chamber_members' folder in public disk.
        $chamberMember->img_path = $path;
        $chamberMember->save();

        // Return a 200 response indicating the image was updated successfully.
        return response()->json([
            'status' => true,
            'message' => 'Chamber member image updated successfully.',
            'data' => $chamberMember,
        ], 200);
    }

    /**
     * Update the specified chamber member in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find the chamber member by ID.
        $chamberMember = ChamberMember::find($id);

        // If the chamber member is not found, return a 404 response.
        if (!$chamberMember) {
            return response()->json([
                'status' => false,
                'message' => 'No chamber member found with the provided ID.'
            ], 404);
        }

        // Define validation rules for chamber member data.
        $rules = [
            'name' => 'sometimes|required|string|max:128',
            'role_es' => 'sometimes|required|string|max:128',
            'role_en' => 'sometimes|required|string|max:128',
            'img_path' => 'sometimes|required|string|max:512',
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

        // Update the chamber member with the provided data.
        $chamberMember->update($request->only(['name', 'role_es', 'role_en', 'img_path']));

        // Return a 200 response indicating the chamber member was updated successfully.
        return response()->json([
            'status' => true,
            'message' => 'Chamber member updated successfully.',
            'data' => $chamberMember
        ], 200);
    }

    /**
     * Remove the specified chamber member from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the chamber member by ID.
        $chamberMember = ChamberMember::find($id);

        // If the chamber member is not found, return a 404 response.
        if (!$chamberMember) {
            return response()->json([
                'status' => false,
                'message' => 'No chamber member found with the provided ID.'
            ], 404);
        }

        // If the chamber member has an image, delete it.
        if ($chamberMember->img_path) {
            Storage::disk('public')->delete($chamberMember->img_path);
        }

        // Delete the chamber member.
        $chamberMember->delete();

        // Return a 200 response indicating the chamber member was deleted successfully.
        return response()->json([
            'status' => true,
            'message' => 'Chamber member deleted successfully.'
        ], 200);
    }

    /**
     * Delete the specified chamber member's image.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage($id)
    {
        // Encontrar el miembro de la cámara por ID.
        $chamberMember = ChamberMember::find($id);

        // Si el miembro no existe, retornar una respuesta 404.
        if (!$chamberMember) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró el miembro de la cámara con el ID proporcionado.'
            ], 404);
        }

        // Verificar si el miembro tiene una imagen previa.
        if ($chamberMember->getRawOriginal('img_path')) {
            // Borrar la imagen del almacenamiento.
            Storage::disk('public')->delete($chamberMember->getRawOriginal('img_path'));

            // Establecer el campo de la ruta de la imagen como nulo.
            $chamberMember->img_path = null;
            $chamberMember->save();

            // Retornar una respuesta indicando que la imagen fue eliminada exitosamente.
            return response()->json([
                'status' => true,
                'message' => 'La imagen del miembro de la cámara se eliminó exitosamente.',
                'data' => $chamberMember
            ], 200);
        }

        // Si no hay imagen, retornar un mensaje indicando que no había imagen para eliminar.
        return response()->json([
            'status' => false,
            'message' => 'El miembro no tiene una imagen para eliminar.'
        ], 400);
    }
}
