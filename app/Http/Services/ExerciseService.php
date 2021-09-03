<?php

namespace App\Http\Services;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ExerciseService {
    public function getExercises() {
        return Exercise::get();
    }

    public function saveExercise(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:exercises',
            'type' => 'required|string',
            'target' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            return Exercise::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'target' => $request->target
            ]);
        }
        catch (QueryException $e) {
            Log::error($e->errorInfo);
            return null;
        }
    }
}
