<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function showForm()
    {
        return view("file-upload");
    }

    public function upload(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the file upload
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            // Redirect to the edit page with the uploaded filename
            return redirect()->route('image.editor', ['filename' => $filename]);
        }

        return back()->with('error', 'Failed to upload image.');
    }

    public function edit($filename)
    {
        // Show the edit page with the uploaded image
        return view('edit-image', compact('filename'));
    }
}
