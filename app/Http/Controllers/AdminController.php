<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Faq;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Dashboard utama admin
    public function dashboard()
    {
        $rooms = Room::all();
        $faqs = Faq::all();
        $pendingPayments = Payment::where('status', 'pending')->count();

        return view('admin.dashboard', compact('rooms', 'faqs', 'pendingPayments'));
    }

    // Room Management
    public function roomIndex()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function roomCreate()
    {
        return view('admin.rooms.create');
    }

    public function roomStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_weekday' => 'required|numeric|min:0',
            'price_weekend' => 'required|numeric|min:0',
            'extra_bed_price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'image' => 'nullable|string'
        ]);

        Room::create($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully!');
    }

    public function roomEdit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.rooms.edit', compact('room'));
    }

    public function roomUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_weekday' => 'required|numeric|min:0',
            'price_weekend' => 'required|numeric|min:0',
            'extra_bed_price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'image' => 'nullable|string'
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully!');
    }

    public function roomDestroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully!');
    }

    // FAQ Management
    public function faqIndex()
    {
        $faqs = Faq::all();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function faqCreate()
    {
        return view('admin.faqs.create');
    }

    public function faqStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string'
        ]);

        Faq::create($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully!');
    }

    public function faqEdit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function faqUpdate(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string'
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully!');
    }

    public function faqDestroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully!');
    }

    // Payment Management
    public function paymentIndex(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.room']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function paymentShow($id)
    {
        $payment = Payment::with(['booking.user', 'booking.room', 'verifiedBy'])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    public function paymentVerify(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:verify,reject',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $payment = Payment::with('booking')->findOrFail($id);

        if ($request->action === 'verify') {
            $payment->update([
                'status' => 'verified',
                'admin_notes' => $request->admin_notes,
                'verified_at' => now(),
                'verified_by' => Auth::guard('admin')->id()
            ]);

            // Update booking status to confirmed
            $payment->booking->update(['status' => 'confirmed']);

            $message = 'Payment verified successfully! Booking has been confirmed.';
        } else {
            $payment->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'verified_at' => now(),
                'verified_by' => Auth::guard('admin')->id()
            ]);

            // Update booking status back to pending
            $payment->booking->update(['status' => 'pending']);

            $message = 'Payment rejected. Customer will need to submit new payment proof.';
        }

        return redirect()->route('admin.payments.index')->with('success', $message);
    }
}
