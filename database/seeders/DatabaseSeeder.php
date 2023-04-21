<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Answer;
use App\Models\Question;
use App\Models\SetQuestion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'name' => 'hieu',
            'email' => 'hieu@gmail.com',
        ]);
        \App\Models\User::factory(10)->create();
        SetQuestion::factory(5)->create();
        $questions = Question::factory(10)->create();
        foreach ($questions as $question) {
            for ($i = 1; $i < 5; $i++) {
                $answer = ["is_correct" => 0];
                if ($i == 4) {
                    $answer = ["is_correct" => 1];
                }
                Answer::factory()->create(["question_id" => $question->id, ...$answer]);
            }
        }
    }
}
