@extends('admin.layouts.app')
@section('title', 'View Message — ' . $message->name)
@section('page_title', 'Message Details')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin.messages.index') }}" 
           class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-2xs hover:bg-slate-50 transition-all w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Messages</span>
        </a>

        <div class="flex items-center gap-2">
            <!-- Toggle Read/Unread Status -->
            <form action="{{ route('admin.messages.toggle-read', $message) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" 
                        class="btn {{ $message->is_read ? 'btn-secondary' : 'btn-primary' }} text-xs py-2 px-3.5 shadow-2xs">
                    @if ($message->is_read)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Mark as Unread</span>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Mark as Read</span>
                    @endif
                </button>
            </form>

            <!-- Reply via Email -->
            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . ($message->subject ?? 'Inquiry via Lima Biji')) }}" 
               class="btn btn-primary text-xs py-2 px-4 shadow-2xs bg-emerald-600 hover:bg-emerald-700 text-white flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                <span>Reply via Email</span>
            </a>

            <!-- Delete Message -->
            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to permanently delete this message?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn text-xs py-2 px-3 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Message Content Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <!-- Message Header & Sender Details -->
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white font-bold flex items-center justify-center text-lg flex-shrink-0 shadow-2xs">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-slate-900">{{ $message->name }}</h2>
                            @if (! $message->is_read)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                    Unread
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Read
                                </span>
                            @endif
                        </div>
                        
                        <div class="text-xs text-slate-600 mt-1 flex flex-wrap items-center gap-y-1 gap-x-3 font-mono">
                            <a href="mailto:{{ $message->email }}" class="text-indigo-600 hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $message->email }}</span>
                            </a>

                            @if ($message->company)
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span>{{ $message->company }}</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-right text-slate-500 font-mono text-xs">
                    <div>{{ $message->created_at ? $message->created_at->format('F d, Y \a\t H:i') : '-' }}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $message->created_at ? $message->created_at->diffForHumans() : '' }}</div>
                </div>
            </div>

            <!-- Subject Banner -->
            <div class="mt-6 pt-5 border-t border-slate-200/80">
                <span class="text-[11px] font-mono font-semibold uppercase tracking-wider text-slate-400">Subject</span>
                <h3 class="text-base font-bold text-slate-900 mt-0.5">
                    {{ $message->subject ?: '(No Subject Provided)' }}
                </h3>
            </div>
        </div>

        <!-- Full Inquiry Message Content -->
        <div class="p-6 sm:p-8">
            <span class="text-[11px] font-mono font-semibold uppercase tracking-wider text-slate-400 block mb-3">Inquiry Message</span>
            <div class="bg-slate-50/75 border border-slate-100 rounded-xl p-5 sm:p-6 text-sm text-slate-800 leading-relaxed font-sans whitespace-pre-wrap selection:bg-indigo-100">
{{ $message->message }}
            </div>
        </div>

        <!-- Footer Card Details -->
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-500 font-mono gap-2">
            <div>
                Received ID: #{{ $message->id }}
            </div>
            @if ($message->read_at)
                <div>
                    First read at: {{ $message->read_at->format('M d, Y H:i') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
