<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JoinRequest;
use Illuminate\Support\Facades\Validator;

class JoinRequestController extends Controller
{
    /**----------INDEX----------
     * Display a listing of join requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get the 'status' parameter from the request
        $status = $request->query('status');

        // Build the query to retrieve join requests
        $query = JoinRequest::query();

        // Check if 'status' is provided and is a valid value (1 to 4)
        if (isset($status) && in_array($status, [1, 2, 3, 4])) {
            $query->where('status', $status);
        }

        // Execute the query and get the results
        $joinRequests = $query->get();

        // Check if any join requests were found
        if ($joinRequests->isEmpty()) {
            return response()->json([
                'status' => false,
                'filter' => $status,
                'message' => 'No join requests found.',
                'data' => []
            ], 404);
        }

        // Return the join requests if found
        return response()->json([
            'status' => true,
            'filter' => $status,
            'message' => 'Join requests retrieved successfully.',
            'data' => $joinRequests,
        ], 200);
    }


    /**----------STORE----------
     * Store a newly created join request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'ins_comercial_name' => 'required|string|max:128',
            'ins_address' => 'required|string|max:256',
            'ins_hood' => 'required|string|max:128',
            'ins_cp' => 'required|string|max:10',
            'ins_email' => 'required|email|max:256',
            'com_capacity' => 'required|integer',
            'com_male' => 'required|integer',
            'com_female' => 'required|integer',
            'com_disabled' => 'required|integer',
            'com_open_date' => 'required|date',
            'com_license_status' => 'required|string|max:2',
            'com_license_type' => 'required|string|max:2',
            'tax_name' => 'required|string|max:128',
            'tax_rfc' => 'required|string|max:16',
            'tax_street' => 'required|string|max:128',
            'tax_hood' => 'required|string|max:128',
            'tax_cp' => 'required|string|max:10',
            'tax_locality' => 'required|string|max:128',
            'tax_payment' => 'required|string|max:2',
            'con_name' => 'required|string|max:128',
            'con_role' => 'required|string|max:96',
            'con_phone' => 'required|string|max:13',
            'con_email' => 'required|email|max:256',
            'com_hours' => 'required|string|max:128',
            'com_line' => 'required|string|max:1024',
            'com_desc' => 'required|string|max:1024',
            'sm_facebook' => 'required|string|max:512',
            'sm_instagram' => 'required|string|max:512',
            'sm_twitter' => 'required|string|max:512',
            'sm_email' => 'required|email|max:256',
            'sm_phone' => 'required|string|max:13',
            'sm_web' => 'required|string|max:512',
            'sv_have_wifi' => 'required|boolean',
            'sv_have_ac' => 'required|boolean',
            'sv_have_live_music' => 'required|boolean',
            'sv_have_deck' => 'required|boolean',
            'sv_have_lounge' => 'required|boolean',
            'sv_lounge_capacity' => 'required|integer',
            'status' => 'required|integer',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $joinRequest = new JoinRequest($request->input());
        $joinRequest->save();

        return response()->json([
            'status' => true,
            'message' => 'Join request created successfully.'
        ], 201);
    }

    /**----------SHOW----------
     * Display the specified join request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $joinRequest = JoinRequest::find($id);

        if (!$joinRequest) {
            return response()->json([
                'status' => false,
                'message' => 'No join request found with the provided ID.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Join request retrieved successfully.',
            'data' => $joinRequest,
        ], 200);
    }

    /**----------UPDATE----------
     * Update the specified join request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Definir las reglas de validación solo para el campo status.
        $rules = [
            'status' => 'required|integer',
        ];

        // Validar el campo status.
        $validator = Validator::make($request->input(), $rules);

        // Si la validación falla, retornar los errores.
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Intentar encontrar la solicitud de unión por su ID.
        $joinRequest = JoinRequest::find($id);

        // Si no se encuentra, retornar un error 404.
        if (!$joinRequest) {
            return response()->json([
                'status' => false,
                'message' => 'No join request found with the provided ID.'
            ], 404);
        }

        // Actualizar solo el campo status.
        $joinRequest->update(['status' => $request->input('status')]);

        // Retornar una respuesta de éxito.
        return response()->json([
            'status' => true,
            'message' => 'Join request status updated successfully.'
        ], 200);
    }


    /**----------DESTROY----------
     * Remove the specified join request from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $joinRequest = JoinRequest::find($id);

        if (!$joinRequest) {
            return response()->json([
                'status' => false,
                'message' => 'No join request found with the provided ID.'
            ], 404);
        }

        $joinRequest->delete();

        return response()->json([
            'status' => true,
            'message' => 'Join request deleted successfully.'
        ], 200);
    }

    public function charts()
    {
        $received = JoinRequest::all()->count();
        $unattended = JoinRequest::where('status', '=', 1)->count();
        $contacted = JoinRequest::where('status', '=', 2)->count();
        $failed = JoinRequest::where('status', '=', 3)->count();
        $joined = JoinRequest::where('status', '=', 4)->count();


        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'received' => $received,
            'unattended' => $unattended,
            'contacted' => $contacted,
            'failed' => $failed,
            'joined' => $joined,
        ], 200);
    }
}
