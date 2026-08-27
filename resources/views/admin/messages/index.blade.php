@extends('admin.layouts.app')
@section('title', 'Inquiries & Messages')
@section('page_title', 'Contact Messages')

@section('content')
<div class="space-y-6">
    <!-- Stat Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.messages.index', ['status' => 'all']) }}" 
           class="card-modern p-5 transition-all hover:border-slate-300 {{ $status === 'all' ? 'border-slate-900 ring-1 ring-slate-900 bg-slate-50/50' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-mono font-medium text-slate-500 uppercase tracking-wider">Total Messages</span>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['all'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}" 
           class="card-modern p-5 transition-all hover:border-emerald-300 {{ $status === 'unread' ? 'border-emerald-500 ring-1 ring-emerald-500 bg-emerald-50/30' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-mono font-medium text-emerald-600 uppercase tracking-wider">Unread Inquiries</span>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['unread'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.messages.index', ['status' => 'read']) }}" 
           class="card-modern p-5 transition-all hover:border-emerald-300 {{ $status === 'read' ? 'border-emerald-500 ring-1 ring-emerald-500 bg-emerald-50/30' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-mono font-medium text-emerald-600 uppercase tracking-wider">Read Inquiries</span>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $counts['read'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Header Toolbar: Search & Status Filter -->
    <div class="card-modern space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Form Input -->
            <form method="GET" action="{{ route('admin.messages.index') }}" class="flex-1 relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search messages by sender name, email, company, subject, or message text..." 
                           class="w-full pl-9 pr-8 text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                    
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.messages.index', ['status' => request('status', 'all')]) }}" 
                           class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                           title="Clear search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Status Tabs Filter -->
            <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl text-xs font-medium">
                <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'all' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    All
                </a>
                <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['status' => 'unread'])) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $status === 'unread' ? 'bg-white text-emerald-700 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    <span>Unread</span>
                    @if ($counts['unread'] > 0)
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    @endif
                </a>
                <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['status' => 'read'])) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'read' ? 'bg-white text-emerald-700 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Read
                </a>
            </div>
        </div>
    </div>

    <!-- Messages List Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-mono">
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-4">Sender Info</th>
                        <th class="py-3.5 px-4">Subject & Message Preview</th>
                        <th class="py-3.5 px-4">Date Received</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse ($messages as $message)
                        <tr class="hover:bg-slate-50/75 transition-colors group {{ ! $message->is_read ? 'bg-emerald-50/20 font-medium' : '' }}">
                            <!-- Status Column -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                @if (! $message->is_read)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Unread
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Read
                                    </span>
                                @endif
                            </td>

                            <!-- Sender Info -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                    <span>{{ $message->name }}</span>
                                </div>
                                <div class="text-slate-500 text-[11px] mt-0.5 font-mono">
                                    <a href="mailto:{{ $message->email }}" class="hover:text-emerald-600 transition-colors">{{ $message->email }}</a>
                                </div>
                                @if ($message->company)
                                    <div class="text-slate-400 text-[10px] mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span>{{ $message->company }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Subject & Preview -->
                            <td class="py-4 px-4 max-w-xs md:max-w-md">
                                @if ($message->subject)
                                    <div class="font-semibold text-slate-900 text-xs mb-0.5 line-clamp-1">
                                        {{ $message->subject }}
                                    </div>
                                @endif
                                <div class="text-slate-600 text-xs line-clamp-2 leading-relaxed">
                                    {{ Str::limit($message->message, 120) }}
                                </div>
                            </td>

                            <!-- Date Received -->
                            <td class="py-4 px-4 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                <div>{{ $message->created_at ? $message->created_at->format('M d, Y') : '-' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $message->created_at ? $message->created_at->format('H:i') . ' (' . $message->created_at->diffForHumans() . ')' : '' }}</div>
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-4 px-5 text-right whitespace-nowrap space-x-1">
                                <!-- View Details -->
                                <a href="{{ route('admin.messages.show', $message) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:text-emerald-600 bg-slate-100 hover:bg-emerald-50 rounded-lg transition-colors"
                                   title="View message">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>Read</span>
                                </a>

                                <!-- Toggle Read / Unread -->
                                <form action="{{ route('admin.messages.toggle-read', $message) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium {{ $message->is_read ? 'text-amber-700 bg-amber-50 hover:bg-amber-100' : 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }} rounded-lg transition-colors"
                                            title="{{ $message->is_read ? 'Mark as Unread' : 'Mark as Read' }}">
                                        @if ($message->is_read)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                        <span>{{ $message->is_read ? 'Unread' : 'Mark Read' }}</span>
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors"
                                            title="Delete message">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-5 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <span class="font-medium text-xs">No messages found</span>
                                    @if(request('search') || request('status') !== 'all')
                                        <a href="{{ route('admin.messages.index') }}" class="text-xs text-emerald-600 hover:underline">Reset filters</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($messages->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
