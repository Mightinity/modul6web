<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller {
    // Menampilkan semua quizzes
    public function index()
    {
        return response()->json(Quiz::with('questions')->get(), 200);
    }

    // Menampilkan quiz spesifik
    public function show($id)
    {
        $quiz = Quiz::with('questions')->find($id);
        if ($quiz) {
            return response()->json($quiz, 200);
        }
        return response()->json(['message' => 'Quiz not found'], 404);
    }

    // Menyimpan quiz baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.options' => 'required|array|size:5', // a, b, c, d, e
            'questions.*.correct_answer' => 'required|string|in:a,b,c,d,e',
        ]);

        // Generate kode quiz unik
        do {
            $code = Str::upper(Str::random(8));
        } while (Quiz::where('code', $code)->exists());

        $quiz = Quiz::create([
            'code' => $code,
            'title' => $request->title,
        ]);

        // Menambahkan pertanyaan
        foreach ($request->questions as $q) {
            $quiz->questions()->create([
                'question_text' => $q['question_text'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
            ]);
        }

        return response()->json($quiz->load('questions'), 201);
    }

    // Memperbarui quiz
    public function update(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            // Anda bisa menambahkan validasi untuk pertanyaan jika perlu
        ]);

        if ($request->has('title')) {
            $quiz->title = $request->title;
        }

        $quiz->save();

        return response()->json($quiz, 200);
    }

    // Menghapus quiz
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        if ($quiz) {
            $quiz->delete();
            return response()->json(['message' => 'Quiz deleted'], 200);
        }
        return response()->json(['message' => 'Quiz not found'], 404);
    }

    // Endpoint tambahan untuk menambahkan pertanyaan ke quiz
    public function addQuestion(Request $request, $quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|size:5',
            'correct_answer' => 'required|string|in:a,b,c,d,e',
        ]);

        $question = $quiz->questions()->create([
            'question_text' => $request->question_text,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
        ]);

        return response()->json($question, 201);
    }

    public function getQuizByCode($code)
    {
        $quiz = Quiz::with('questions')->where('code', $code)->first();
        if ($quiz) {
            return response()->json($quiz, 200);
        }
        return response()->json(['message' => 'Quiz not found'], 404);
    }

}

