@extends('admin.layouts.app')
@section('title', 'Users')
@section('page_title', 'User Accounts & Roles')

@section('content')
<div class="card-modern">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Administrator & Staff Users</h3>
            <p class="text-xs text-slate-500">Manage portal access permissions, roles, and administrative accounts.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">{{ count($users) }} Users Total</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary py-1.5 px-3.5 text-xs shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email Address</th>
                    <th>System Role</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td class="font-semibold text-slate-900">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span>{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="font-mono text-xs text-slate-600">{{ $user->email }}</td>
                    <td>
                        @if ($user->role === 'admin')
                            <span class="badge bg-slate-100 text-slate-800 border border-slate-200 font-semibold">Administrator</span>
                        @elseif ($user->role === 'editor')
                            <span class="badge bg-slate-100 text-slate-700 border border-slate-200">Editor</span>
                        @else
                            <span class="badge bg-slate-100 text-slate-700 border border-slate-200">{{ ucfirst($user->role) }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary py-1.5 px-3 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            @if ($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary text-rose-600 hover:text-rose-700 hover:bg-rose-50 border-rose-200 py-1.5 px-2.5 text-xs font-semibold">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-10 text-slate-400">
                        No registered users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection