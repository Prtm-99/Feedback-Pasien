<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::all();
        return view('admin.topic.index', compact('topics'));
    }

    public function create()
    {
        return view('admin.topic.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Topic::create($request->only('name'));

        return redirect()->route('admin.topic.index')->with('success', 'Topik berhasil ditambahkan.');
    }

    public function edit(Topic $topic)
    {
        return view('admin.topic.edit', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $topic->update($request->only('name'));

        return redirect()->route('admin.topic.index')->with('success', 'Topik berhasil diperbarui.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('admin.topic.index')->with('success', 'Topik berhasil dihapus.');
    }
}


