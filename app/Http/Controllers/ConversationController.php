<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $conversations = Conversation::query()
            ->where(function ($q) use ($user) {
                $q->where('participant_one_id', $user->id)
                    ->orWhere('participant_two_id', $user->id);
            })
            ->with(['participantOne', 'participantTwo', 'latestMessage', 'listing'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('dashboard.messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        abort_unless($conversation->hasParticipant(auth()->user()), 403);

        $messages = $conversation->messages()
            ->with('sender')
            ->paginate(50);

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $otherParticipant = $conversation->getOtherParticipant(auth()->user());

        return view('dashboard.messages.show', compact('conversation', 'messages', 'otherParticipant'));
    }

    public function start(Request $request, Listing $listing): RedirectResponse
    {
        $user = auth()->user();

        abort_if($listing->isOwnedBy($user), 422, 'Нельзя написать самому себе.');

        $conversation = Conversation::firstOrCreate([
            'participant_one_id' => min($user->id, $listing->user_id),
            'participant_two_id' => max($user->id, $listing->user_id),
            'listing_id' => $listing->id,
        ]);

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => $validated['body'],
            'type' => 'text',
        ]);

        $conversation->update(['last_message_at' => now()]);
        $listing->increment('responses_count');

        return redirect()->route('messages.show', $conversation)
            ->with('success', 'Сообщение отправлено.');
    }

    public function reply(Request $request, Conversation $conversation): RedirectResponse
    {
        abort_unless($conversation->hasParticipant(auth()->user()), 403);

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'body' => $validated['body'],
            'type' => 'text',
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back()->with('success', 'Сообщение отправлено.');
    }
}
