<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;

class Clients extends Controller
{
    // Get all completed clients
    public function index()
    {
        $clients = Appointment::where('status', 'Completed')
            ->with('account')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'All completed clients successfully fetched',
            'data' => $clients,
        ]);
    }

    // Get a single client with financial details
    public function show($id)
    {
        $client = Appointment::where('status', 'Completed')
            ->where('id', $id)
            ->with('account')
            ->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $client,
        ]);
    }

    // Update client details
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string',
            'email' => 'sometimes|email|max:255',
            'service' => 'sometimes|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $client = Appointment::where('status', 'Completed')
            ->where('id', $id)
            ->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found '.$id,
            ], 404);
        }

        $client->update($request->only(['name', 'phone', 'email', 'service', 'notes']));

        return response()->json([
            'success' => true,
            'message' => 'Client updated successfully',
            'data' => $client,
        ]);
    }

    // Delete client (soft delete)
    public function destroy($id)
    {
        $client = Appointment::where('status', 'Completed')
            ->where('id', $id)
            ->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found',
            ], 404);
        }

        // Delete associated account records first
        Account::where('client_id', $id)->delete();
        
        // Soft delete the client
        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully',
        ]);
    }

    // Add/Update financial record for client
    public function addFinancialRecord(Request $request, $clientId)
    {
        $validator = Validator::make($request->all(), [
            'amount_paid' => 'required|numeric|min:0',
            'service_cost' => 'required|numeric|min:0',
            'material_cost' => 'nullable|numeric|min:0',
            'other_cost' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $client = Appointment::where('status', 'Completed')
            ->where('id', $clientId)
            ->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found',
            ], 404);
        }

        // Update or create financial record
        $account = Account::updateOrCreate(
            ['client_id' => $clientId],
            $request->all()
        );

        return response()->json([
            'success' => true,
            'message' => 'Financial record saved successfully',
            'data' => $account,
        ]);
    }

    // Get financial summary (profit/loss)
    public function getFinancialSummary()
    {
        $summary = Account::selectRaw('
            COUNT(*) as total_clients,
            SUM(amount_paid) as total_revenue,
            SUM(service_cost) as total_service_cost,
            SUM(material_cost) as total_material_cost,
            SUM(other_cost) as total_other_cost,
            SUM(service_cost + material_cost + other_cost) as total_cost,
            SUM(amount_paid - (service_cost + material_cost + other_cost)) as total_profit
        ')->first();

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }
}