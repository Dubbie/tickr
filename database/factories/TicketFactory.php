<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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

        // Randomize creation and update dates
        $createdAt = $this->faker->dateTimeBetween('-30 days', 'now');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

        $status = $this->faker->randomElement(Ticket::STATUSES);

        $assignedTo = null;
        if ($status !== Ticket::STATUSES['0']) {
            $assignedTo = User::inRandomOrder()->first()->id;
        }

        return [
            'customer_uuid' => $customer->uuid,
            'contact_email' => $customer->email,
            'assigned_to' => $assignedTo,
            'subject' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $status,
            'priority' => $this->faker->randomElement(Ticket::PRIORITIES),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Ticket $ticket) {
            if ($ticket->status !== Ticket::STATUSES['0']) {
                $replyCount = rand(1, 5);

                for ($i = 0; $i < $replyCount; $i++) {
                    TicketReply::factory()->forTicket($ticket)->create();
                }
            }
        });
    }
}
