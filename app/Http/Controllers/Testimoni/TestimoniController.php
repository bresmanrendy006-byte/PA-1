<?php

namespace App\Http\Controllers\Testimoni;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimoniRequest;
use App\Models\Order;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    /**
     * Check whether the user has at least 1 confirmed order.
     */
    private function hasConfirmedOrder(): bool
    {
        return Order::where('user_id', Auth::id())
            ->where('status', Order::STATUS_CONFIRMED)
            ->exists();
    }

    public function index()
    {
        $testimonials = Testimoni::with('user')->latest()->get();

        $hasConfirmedOrder = Auth::check() ? $this->hasConfirmedOrder() : false;

        return view('guest.pages.testimoni', compact('testimonials', 'hasConfirmedOrder'));
    }

    public function store(StoreTestimoniRequest $request)
    {
        if (!Auth::user()->isCustomer()) {
            abort(403, 'Only customers can submit a testimonial.');
        }

        if (!$this->hasConfirmedOrder()) {
            return back()->with('error', 'You must have a confirmed order to submit a testimonial.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('testimonials', 'public');
        }

        Testimoni::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'rating'  => $request->rating,
            'photo'   => $photoPath,
        ]);

        return back()->with('success', 'Testimonial added successfully.');
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimoni::findOrFail($id);

        if ($testimonial->user_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'rating'  => 'required|integer|min:1|max:5',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $data = [
            'content' => $request->content,
            'rating'  => $request->rating,
        ];

        if ($request->hasFile('photo')) {
            if ($testimonial->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
            $data['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        return back()->with('success', 'Testimonial updated successfully.');
    }

    public function destroy($id)
    {
        $testimonial = Testimoni::findOrFail($id);

        if ($testimonial->user_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted successfully.');
    }
}