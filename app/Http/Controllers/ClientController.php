<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document; // Import the Document model
use App\Models\Check; // Import the Check model
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClientController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        $clients = Client::with(['documents', 'checks' => function ($query) {
            $query->where('completed', true);
        }])->withCount(['checks' => function ($query) {
            $query->where('completed', true);
        }])->get();

        $totalClients = $clients->count();
        $clientsYesterday = Client::whereDate('created_at', Carbon::yesterday())->count();
        $clientsDayBeforeYesterday = Client::whereDate('created_at', Carbon::yesterday()->subDay())->count();

        $percentageIncrease = 0;
        if ($clientsDayBeforeYesterday > 0) {
            $percentageIncrease = (($clientsYesterday - $clientsDayBeforeYesterday) / $clientsDayBeforeYesterday) * 100;
        }

        return view('clients.index', compact('clients', 'totalClients', 'percentageIncrease', 'clientsYesterday'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $client = Client::create($validated);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents');
            Document::create([
                'client_id' => $client->id,
                'document_name' => $request->file('document')->getClientOriginalName(),
                'document_path' => $path,
            ]);
            $client->status_708 = true;
            $client->save();
        }

        // Create an initial check for the client
        Check::create([
            'client_id' => $client->id,
            'completed' => false, // Set this as needed
        ]);

        return redirect()->back()->with('success', 'Client added successfully!');
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $client->id,
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $client->update($validated);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents');
            Document::create([
                'client_id' => $client->id,
                'document_name' => $request->file('document')->getClientOriginalName(),
                'document_path' => $path,
            ]);
            $client->status_708 = true;
            $client->save();
        }

        return redirect()->back()->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        $client->documents()->delete(); // Ensure documents are deleted first
        $client->checks()->delete(); // Ensure checks are deleted first
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully!');
    }

    public function createVerificationSession(Client $client)
    {
        try {
            Log::info('Creating verification session for client: ' . $client->id);
            $session = $this->stripeService->createVerificationSession($client);
            Log::info('Verification session created with ID: ' . $session->id);
            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Failed to create verification session: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to create verification session: ' . $e->getMessage());
        }
    }

    public function handleVerificationCallback(Request $request)
    {
        $sessionId = $request->query('session_id');
        Log::info('Received verification callback for session ID: ' . $sessionId);

        try {
            $session = $this->stripeService->retrieveVerificationSession($sessionId);
            Log::info('Full verification session object: ' . json_encode($session));

            if ($session->status === 'verified') {
                $clientId = $session->metadata->client_id;
                Log::info('Client ID from metadata: ' . $clientId);
                $client = Client::find($clientId);
                if ($client) {
                    Log::info('Client found: ' . $client->id);
                    $client->identity_verified = true;
                    $client->save();
                    Log::info('Client verification status updated: ' . $client->identity_verified);
                } else {
                    Log::error('Client not found for session ID: ' . $sessionId);
                    return redirect()->route('clients.index')->withErrors('Client not found for the verification session.');
                }
            } else {
                Log::error('Verification session not verified: ' . $session->status);
                return redirect()->route('clients.index')->withErrors('Verification session not verified.');
            }

            return redirect()->route('clients.index')->with('success', 'Client verification status updated.');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve verification session: ' . $e->getMessage());
            return redirect()->route('clients.index')->withErrors('Failed to retrieve verification session: ' . $e->getMessage());
        }
    }
}
