<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a random customer or create one if none exists
        $customer = Customer::inRandomOrder()->first() ?? Customer::factory()->create();

        // Normalize the customer's name
        $customerName = strtoupper(preg_replace('/[^A-Z]/', '', Str::ascii($customer->name)));
        $prefix = substr($customerName, 0, 4); // Limit to first 4 characters

        // Find the latest ticket for this customer to increment the number
        $latestTicket = Ticket::where('ticket_number', 'like', "$prefix-%")
            ->orderBy('ticket_number', 'desc')
            ->first();

        // Increment the ticket number or set to 1 if no tickets exist
        $nextNumber = $latestTicket
            ? ((int) Str::afterLast($latestTicket->ticket_number, '-') + 1)
            : 1;

        // Format the ticket number
        $ticketNumber = sprintf('%s-%03d', $prefix, $nextNumber);

        return [
            'ticket_number' => $ticketNumber,
            'customer_uuid' => $customer->uuid,
            'contact_email' => $customer->email,
            'subject' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(Ticket::STATUSES),
            'priority' => $this->faker->randomElement(Ticket::PRIORITIES),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
