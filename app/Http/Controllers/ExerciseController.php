<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Managers\APIManager;

class ExerciseController extends Controller
{

    private APIManager $manager;

    public function __construct(APIManager $manager){
        $this->manager = $manager;
    }

    public function index(Request $request) {
        $response = $this->manager->getExerciseService()->getExercises();

        return $response;
    }

    public function store(Request $request) {
        $response = $this->manager->getExerciseService()->saveExercise($request);

        if ($response !== null) {
            return $response;
        }

        return response()->json(['error' => 'Exercise creation failed'], 500);
    }
}
