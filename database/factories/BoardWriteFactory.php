<?php

namespace Database\Factories;

use App\Models\BoardWrite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BoardWrite>
 */
class BoardWriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'board_id' => 1,
            'table_id' => 'default',
            'categories' => fake()->randomElement(['구글','애플','네이버','카카오','페이스북','인스타그램','트위터','유튜브']),
            'user_id' => fake()->numberBetween(2, 31),
            'subject' => fake()->sentence(10),
            'content' => fake()->paragraph(12),
            'tags' => implode(',', fake()->randomElements(['Java','PHP','Laravel','Node','React','Python','Nest.js','Spring','Django','Flask','FastAPI','Ruby on Rails','Express','Next.js','Nuxt.js','Vue.js','Angular','jQuery','Bootstrap','Tailwind CSS','Sass','Less','Styled Components','Material UI'], 3)),
            'hit' => fake()->numberBetween(0, 100),
            'writer' => fake()->name(),
            'ip' => fake()->ipv4(),
        ];
    }
}
