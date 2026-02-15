<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeadConversionService
{
    /**
     * Convert a Lead into a Client (create new OR link existing).
     * Returns the Client.
     */
    public function convert(Lead $lead, array $payload, int $actorId): Client
    {
        if ($lead->converted_client_id) {
            throw ValidationException::withMessages([
                'lead' => 'This lead is already converted.',
            ]);
        }

        return DB::transaction(function () use ($lead, $payload, $actorId) {
            $mode = $payload['mode'] ?? 'create';

            $client = null;

            if ($mode === 'link') {
                $client = Client::query()->findOrFail((int) $payload['existing_client_id']);
            } else {
                // Create new client (use lead fallback if not provided)
                $name = $payload['name'] ?: $lead->name;

                $client = Client::query()->create([
                    'name' => $name,
                    'email' => $payload['email'] ?? $lead->email,
                    'phone' => $payload['phone'] ?? $lead->phone,
                    'company_name' => $payload['company_name'] ?? null,
                    'address' => $payload['address'] ?? null,
                    'country' => $payload['country'] ?? null,
                    'status' => 'active',
                    'custom_fields' => [
                        'source' => $lead->source,
                        'lead_id' => (int) $lead->id,
                    ],
                ]);
            }

            // Mark lead converted
            $lead->forceFill([
                'converted_client_id' => (int) $client->id,
                'converted_at' => now(),
                'status' => Lead::STATUS_CONVERTED,
            ])->save();

            // Activity log (Lead)
            Activity::query()->create([
                'subject' => 'Lead converted to Client',
                'type' => 'note',
                'body' => 'Converted to Client #' . $client->id . ' (' . ($client->name ?? '-') . ')',
                'activity_at' => now(),
                'next_follow_up_at' => null,
                'status' => 'done',
                'actor_id' => $actorId,
                'actionable_type' => Lead::class,
                'actionable_id' => (int) $lead->id,
            ]);

            // Activity log (Client)
            Activity::query()->create([
                'subject' => 'Client created from Lead',
                'type' => 'note',
                'body' => 'Created/linked from Lead #' . $lead->id . ' (' . ($lead->name ?? '-') . ')' .
                    (!empty($payload['notes']) ? ("\n\nNotes:\n" . $payload['notes']) : ''),
                'activity_at' => now(),
                'next_follow_up_at' => null,
                'status' => 'open',
                'actor_id' => $actorId,
                'actionable_type' => Client::class,
                'actionable_id' => (int) $client->id,
            ]);

            return $client;
        });
    }
}
