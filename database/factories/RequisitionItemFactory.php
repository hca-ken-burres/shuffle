<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Requisition;
use App\Models\RequisitionItem;

class RequisitionItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RequisitionItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->randomNumber(),
            'description' => $this->faker->text(),
            'code' => $this->faker->word(),
            'unit_price' => $this->faker->randomFloat(2, 0, 999999.99),
            'requisition_id' => Requisition::factory(),
        ];
    }
}
