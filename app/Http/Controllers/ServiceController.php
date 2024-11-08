<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**----------INDEX----------
     * Display a listing of services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get the language parameter from the request, default is 'es'.
        $language = $request->input('lang', 'es');

        // Get all services from the database.
        $services = Service::all();

        // If no services are found, return a 404 response.
        if ($services->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No services found.',
                'data' => []
            ], 404);
        }

        // Return a 200 response with the service data.
        return response()->json([
            'status' => true,
            'message' => 'Services retrieved successfully.',
            'data' => $services->map(function ($service) use ($language) {
                // Return service data, including language-specific fields.
                return [
                    'id' => $service->id,
                    'title' => $service->{'title_' . $language},
                    'description' => $service->{'description_' . $language},
                    'contact_name' => $service->contact_name,
                    'phone' => $service->phone,
                    'img_path' => $service->img_path // Use only img_path
                ];
            })
        ], 200);
    }

    /**----------INDEX DASH----------
     * Display a listing of services without language filtering.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexDash()
    {
        // Retrieve all services from the database.
        $services = Service::all();

        // Check if any services were found.
        if ($services->isEmpty()) {
            // If no services are found, return a 404 response with a custom message.
            return response()->json([
                'status' => false,
                'message' => 'No services found.',
                'data' => []
            ], 404);
        }

        // If services are found, return a 200 response with the services data.
        return response()->json([
            'status' => true,
            'message' => 'Services retrieved successfully.',
            'data' => $services // No need to map, return the data as is.
        ], 200);
    }

    /**----------STORE----------
     * Store a newly created service in storage.
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
            'contact_name' => 'required|string|max:128',
            'phone' => 'required|string|max:13',
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

        // Extract valid data from the request, excluding images.
        $data = $request->only(['title_es', 'title_en', 'description_es', 'description_en', 'contact_name', 'phone']);

        // If an image was uploaded, store it and add the path to data array.
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $path = $image->store('services', 'public'); // Store image in the 'services' folder in public disk.
            $data['img_path'] = $path; // Add image path to data array.
        }

        // Create a new Service object with the provided data and save it to the database.
        $service = new Service($data);
        $service->save();

        // Return a JSON response indicating that the service was created successfully, along with service data.
        return response()->json([
            'status' => true,
            'message' => 'Service created successfully',
            'data' => $service,
        ], 200);
    }

    /**----------SHOW----------
     * Display the specified service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        // Attempt to find the service by ID.
        $service = Service::find($id);

        // If the service is not found, return a 404 response with an error message.
        if (!$service) {
            return response()->json([
                'status' => false,
                'message' => 'No service found with the provided ID.'
            ], 404);
        }

        // Get the language parameter from the request, default is 'es'.
        $language = $request->query('lang', 'es');

        // Return a 200 response with the service data.
        return response()->json([
            'status' => true,
            'message' => 'Service retrieved successfully.',
            'data' => [
                'id' => $service->id,
                'title' => $service->{'title_' . $language},
                'description' => $service->{'description_' . $language},
                'contact_name' => $service->contact_name,
                'phone' => $service->phone,
                'img_path' => $service->img_path // Use only img_path
            ]
        ], 200);
    }

    /**----------UPDATE DATA----------
     * Update the service data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateData(Request $request, $id)
    {
        // Define validation rules for the service data.
        $rules = [
            'title_es' => 'required|string|max:256',
            'title_en' => 'required|string|max:256',
            'description_es' => 'required|string|max:2048',
            'description_en' => 'required|string|max:2048',
            'contact_name' => 'required|string|max:128',
            'phone' => 'required|string|max:13',
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

        // Find the service by ID.
        $service = Service::find($id);

        // If the service is not found, return a 404 response.
        if (!$service) {
            return response()->json([
                'status' => false,
                'message' => 'No service found with the provided ID.'
            ], 404);
        }

        // Update the service with the provided data.
        $service->update($request->all());

        // Return a 200 response indicating the service data was updated successfully.
        return response()->json([
            'status' => true,
            'message' => 'Service data updated successfully.',
            'data' => $service
        ], 200);
    }

    /**----------UPDATE IMAGE----------
     * Update the service image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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

        // Find the service by ID.
        $service = Service::find($id);

        // If the service is not found, return a 404 response.
        if (!$service) {
            return response()->json([
                'status' => false,
                'message' => 'No service found with the provided ID.'
            ], 404);
        }

        // If the service has a previous image, delete it.
        if ($service->getRawOriginal('img_path')) {
            Storage::disk('public')->delete($service->getRawOriginal('img_path'));
        }

        // Store the new image and update the service's image path.
        $image = $request->file('img');
        $path = $image->store('services', 'public');
        $service->img_path = $path;
        $service->save();

        // Return a 200 response indicating the image was updated successfully.
        return response()->json([
            'status' => true,
            'message' => 'Service image updated successfully.',
            'data' => $service,
        ], 200);
    }

    /**----------DESTROY----------
     * Remove the specified service from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the service by ID.
        $service = Service::find($id);

        // If the service is not found, return a 404 response.
        if (!$service) {
            return response()->json([
                'status' => false,
                'message' => 'No service found with the provided ID.'
            ], 404);
        }

        // If the service has an image, delete it from storage.
        if ($service->getRawOriginal('img_path')) {
            Storage::disk('public')->delete($service->getRawOriginal('img_path'));
        }

        // Delete the service from the database.
        $service->delete();

        // Return a 200 response indicating the service was deleted successfully.
        return response()->json([
            'status' => true,
            'message' => 'Service deleted successfully.',
        ], 200);
    }

    /**
     * Delete the specified service's image.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage($id)
    {
        // Encontrar el miembro de la cámara por ID.
        $service = Service::find($id);

        // Si el miembro no existe, retornar una respuesta 404.
        if (!$service) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró el servicio con el ID proporcionado.'
            ], 404);
        }

        // Verificar si el miembro tiene una imagen previa.
        if ($service->getRawOriginal('img_path')) {
            // Borrar la imagen del almacenamiento.
            Storage::disk('public')->delete($service->getRawOriginal('img_path'));

            // Establecer el campo de la ruta de la imagen como nulo.
            $service->img_path = null;
            $service->save();

            // Retornar una respuesta indicando que la imagen fue eliminada exitosamente.
            return response()->json([
                'status' => true,
                'message' => 'La imagen del servicio se eliminó exitosamente.',
                'data' => $service
            ], 200);
        }

        // Si no hay imagen, retornar un mensaje indicando que no había imagen para eliminar.
        return response()->json([
            'status' => false,
            'message' => 'El servicio no tiene una imagen para eliminar.'
        ], 400);
    }
}
