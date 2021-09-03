<?php

namespace App\Http\Managers;

use App\Http\Services\ExerciseService;

class APIManager {
    public function getExerciseService() {
        return resolve(ExerciseService::class);
    }
}
