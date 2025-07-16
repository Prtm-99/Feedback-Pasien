<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Topic;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('topic')->get();
        return view('admin.question.index', compact('questions'));
    }

    public function create()
    {
        $topics = Topic::all();
        return view('admin.question.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'text' => 'required|string|max:255'
        ]);

        Question::create($request->only('topic_id', 'text'));

        return redirect()->route('admin.question.index')->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function edit(Question $question)
    {
        $topics = Topic::all();
        return view('admin.question.edit', compact('question', 'topics'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'text' => 'required|string|max:255'
        ]);

        $question->update($request->only('topic_id', 'text'));

        return redirect()->route('admin.question.index')->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.question.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }
}

