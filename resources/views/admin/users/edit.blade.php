@extends('admin.layouts.app')
@section('title', $user->exists ? 'Edit User' : 'Create User')
@section('page_title', $user->exists ? 'Edit User: ' . $user->name : 'Create New User')

@section('content')
<form action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
    @csrf
    @if ($user->exists)
        @method('PUT')
    @endif
    <div class="max-w-lg">
        <div class="card-modern space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">{{ $user->exists ? 'User Account Settings' : 'New Account Credentials' }}</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-slate-500 hover:text-emerald-600 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Users
                </a>
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full text-xs bg-white border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full text-xs bg-white border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500">
                @error('email')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">System Access Role <span class="text-rose-500">*</span></label>
                <select name="role" id="role" required class="w-full text-xs bg-white border @error('role') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Full Access)</option>
                    <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor (Content & Article Access)</option>
                </select>
                @error('role')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">{{ $user->exists ? 'New Password (leave blank to keep current)' : 'Account Password' }}</label>
                <input type="password" name="password" id="password" minlength="8" {{ $user->exists ? '' : 'required' }} placeholder="••••••••" class="w-full text-xs bg-white border @error('password') border-rose-500 @else border-slate-200 @enderror rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500">
                @error('password')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full py-2.5 text-xs font-semibold shadow-xs mt-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $user->exists ? 'Save User Account' : 'Create User Account' }}
            </button>
        </div>
    </div>
</form>
@endsection