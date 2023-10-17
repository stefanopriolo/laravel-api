<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $tags = ['Vue', 'React', 'JS', 'TS', 'CSS', "SCSS", "Bootstrap", "Tailwind", "PHP", "Laravel", "NodeJs"];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => \Illuminate\Support\Str::slug($tag),
                'color' => $faker->rgbColor(),
                'icon' => "",
                "description" => $faker->text()
            ]);
        }
    }
}
