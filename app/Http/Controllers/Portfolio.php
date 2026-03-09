<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SignatureLook;
use Illuminate\Support\Facades\Storage;

class Portfolio extends Controller
{
    public function index()
    {
        return SignatureLook::latest()->get();
    }

    public function showActive()
    {
        return SignatureLook::where('status', 'active')->latest()->get();
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'tag' => 'required|string',
            'image' => 'nullable|max:2048',

        ]);

        if ($request->hasFile('image')) {
            // $data['image'] = $request->file('image')->store('signatures', 'public');
            return response()->json(['success' => 200, 'data' => 'Image is present']);
        }
        return response()->json(['data' => $data]);

        return SignatureLook::create($data);
    }

    public function update(Request $request, SignatureLook $signatureLook)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'tag' => 'required|string',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            if ($signatureLook->image) {
                Storage::disk('public')->delete($signatureLook->image);
            }
            $data['image'] = $request->file('image')->store('signatures', 'public');
        }

        $signatureLook->update($data);

        return $signatureLook;
    }

    public function destroy(SignatureLook $signatureLook)
    {
        if ($signatureLook->image) {
            Storage::disk('public')->delete($signatureLook->image);
        }
        $signatureLook->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function toggleStatus(SignatureLook $signatureLook)
    {
        $signatureLook->status =  $signatureLook->status === 'active' ? 'inactive' : 'active';
        $signatureLook->save();

        return response()->json([
            // 'status' => $signatureLook->status
            'success' => true,
            'data' => $signatureLook->status,
        ]);
    }
}
