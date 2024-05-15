<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\RequisitionCategory;
use App\Enums\RequisitionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Requisition;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Year;
use Psr\Log\NullLogger;

class RequisitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Requisition::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $submitted = rand(0,1);
        $approved = $submitted && rand(0,1);
        $ordered = $approved && rand(0,1);

        $status = match($submitted + $approved + $ordered) {
            0 => RequisitionStatus::DRAFT,
            1 => RequisitionStatus::SUBMITTED,
            2 => RequisitionStatus::APPROVED,
            3 => RequisitionStatus::ORDERED,
        };

        $method = $ordered 
            ? $this->faker->RandomElement(PaymentMethod::class)
            : null;

        $note = ($method == PaymentMethod::PO)
            ? $this->faker->regexify('2425-[0-9]{2,3}')
            : null;

        return [
            'category' => $this->faker->randomElement(RequisitionCategory::class),
            'reason' => $this->faker->sentence(),
            'submitted_at' => $submitted
                ? $this->faker->dateTimeBetween('+1 days','+3 days')
                : null,
            'admin_id' => null,
            'approved_at' => $approved 
                ? $this->faker->dateTimeBetween('+4 days', '+7 days')
                : null,
            'orderer_id' => null,
            'account_num' => $ordered ? $this->faker->regexify('[0-9]{6}') : null,
            'ordered_at' => null,
            'received_at' => null,
            'payment_method' => $method,
            'payment_note' => $note,
            'user_id' => User::count() 
                ? $this->faker->randomElement(User::pluck('id')->toArray())
                : User::factory(),
            'vendor_id' => Vendor::factory(),
            'year_id' => $this->faker->randomElement(Year::pluck('id')->toArray()),
            'status' => $status,
        ];
    }
}
