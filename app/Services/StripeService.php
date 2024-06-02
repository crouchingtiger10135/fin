<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Identity\VerificationSession;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createVerificationSession($client)
    {
        try {
            $session = VerificationSession::create([
                'type' => 'document',
                'metadata' => [
                    'client_id' => $client->id,
                ],
                'return_url' => route('clients.verification.callback'),
            ]);

            return $session;
        } catch (\Exception $e) {
            Log::error('Stripe Verification Session Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveVerificationSession($sessionId)
    {
        try {
            return VerificationSession::retrieve($sessionId);
        } catch (\Exception $e) {
            Log::error('Stripe Verification Session Retrieval Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
