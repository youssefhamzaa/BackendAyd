<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        try {
            $clients = Client::all();
            return response()->json($clients);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'Nom' => 'required|string|max:255',
                'Email' => 'required|email|unique:clients,Email',
                'Adresse' => 'required|string',
                'Phone_1' => 'required|string',
                'Phone_2' => 'nullable|string',
                'Gouvernorat' => 'required|string',
                'Delegation' => 'required|string',
            ]);

            $client = Client::create($validated);
            return response()->json(['message' => 'Client created successfully', 'data' => $client], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified client.
     */
    public function show($id)
    {
        try {
            $client = Client::findOrFail($id);
            return response()->json($client);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'Nom' => 'required|string|max:255',
                'Email' => 'required|email|unique:clients,Email,' . $id,
                'Adresse' => 'required|string',
                'Phone_1' => 'required|string',
                'Phone_2' => 'nullable|string',
                'Gouvernorat' => 'required|string',
                'Delegation' => 'required|string',
            ]);

            $client = Client::findOrFail($id);
            $client->update($validated);
            return response()->json(['message' => 'Client updated successfully', 'data' => $client]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json(['message' => 'Client deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function countClients()
    {
        try {
            // Count the number of subcategories
            $count = Client::count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
