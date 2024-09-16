<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageEditorController extends Controller
{
    public function showForm()
    {
        return view('image-editor');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            return redirect()->route('image.editor', ['filename' => $filename]);
        }

        return back()->with('error', 'Failed to upload image.');
    }

    public function edit($filename)
    {
        return view('edit-image', compact('filename'));
    }
}
