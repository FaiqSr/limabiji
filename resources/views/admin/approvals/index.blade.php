@extends('admin.layouts.app')
@section('title', 'Approvals')
@section('page_title', 'Approvals Queue')

@section('content')
<div class="card-modern">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Pending Article Submissions</h3>
            <p class="text-xs text-slate-500">Articles awaiting editorial approval before public publication.</p>
        </div>
        <span class="badge badge-pending">{{ count($pendingArticles) }} Pending</span>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Article Title</th>
                    <th>Author</th>
                    <th>Submitted</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingArticles as $article)
                <tr>
                    <td class="font-medium text-slate-900">{{ $article->title }}</td>
                    <td class="text-slate-600">{{ $article->author?->name ?? '—' }}</td>
                    <td class="text-xs text-slate-500 font-mono">{{ $article->updated_at->diffForHumans() }}</td>
                    <td class="text-right">
                        <div class="inline-flex items-center gap-2 justify-end">
                            <form action="{{ route('admin.approvals.approve', $article) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success py-1.5 px-3 text-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.approvals.reject', $article) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger py-1.5 px-3 text-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-12 text-slate-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-slate-500">No pending article approvals at this time.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection