<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketReply>
 */
class TicketReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Default fields, to be overridden in relationships
            'ticket_uuid' => null, // To be set by relationships
            'message' => $this->faker->paragraph,
            'replier_type' => null, // Set dynamically in afterMaking/afterCreating
            'replier_id' => null,   // Set dynamically in afterMaking/afterCreating
            'email' => null,        // Null for users, dynamically set for customers
            'created_at' => now(),  // Dynamically adjusted later
            'updated_at' => now(),
        ];
    }

    /**
     * Configure factory relationships and customizations.
     */
    public function configure()
    {
        return $this->afterMaking(function ($ticketReply) {
            // Ensure a Ticket exists and set its relationship
            if (!$ticketReply->ticket_uuid) {
                $ticket = Ticket::factory()->create();
                $ticketReply->ticket_uuid = $ticket->uuid;
            }

            // Assign a random replier: User or Customer
            $isUser = $this->faker->boolean;

            if ($isUser) {
                $user = User::inRandomOrder()->first() ?? User::factory()->create();
                $ticketReply->replier_type = User::class;
                $ticketReply->replier_id = $user->id;
                $ticketReply->email = null; // Users don't have email
            } else {
                $ticket = Ticket::where('uuid', $ticketReply->ticket_uuid)->first();
                $ticketReply->replier_type = 'App\Models\Customer';
                $ticketReply->replier_id = $ticket->customer->uuid;
                $ticketReply->email = $ticket->customer->email;
            }

            // Adjust the reply's created_at to follow the ticket's created_at
            $ticket = Ticket::where('uuid', $ticketReply->ticket_uuid)->first();
            $ticketReply->created_at = $this->faker->dateTimeBetween(
                $ticket->created_at->addMinute(),
                $ticket->created_at->addHour()
            );

            if (!$ticket->time_to_first_reply) {
                $ticket->update([
                    'time_to_first_reply' => $ticket->created_at->diffInMinutes($ticketReply->created_at)
                ]);
            }
        });
    }

    /**
     * Associate with a specific ticket.
     */
    public function forTicket(Ticket $ticket)
    {
        return $this->state(fn() => ['ticket_uuid' => $ticket->uuid]);
    }
}
