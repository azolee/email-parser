<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuccessfulEmail\StoreSuccessfulEmailRequest;
use App\Http\Requests\SuccessfulEmail\UpdateSuccessfulEmailRequest;
use App\Http\Resources\SuccessfulEmailResource;
use App\Models\SuccessfulEmail;
use Illuminate\Http\Request;

class SuccessfulEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // @todo Add filtering, sorting and extract pagination count to be customizable from config
        return SuccessfulEmailResource::collection(SuccessfulEmail::whereNull('deleted_at')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSuccessfulEmailRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['timestamp'] = $validatedData['timestamp'] ?? time();
        $email = SuccessfulEmail::create($validatedData);
        return new SuccessfulEmailResource($email);
    }

    /**
     * Display the specified resource.
     */
    public function show(SuccessfulEmail $email)
    {
        return new SuccessfulEmailResource($email);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSuccessfulEmailRequest $request, SuccessfulEmail $email)
    {
        $email->update($request->validated());
        return new SuccessfulEmailResource($email);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuccessfulEmail $email)
    {
        $email->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}
