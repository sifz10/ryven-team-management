<x-client-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Team Members</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your team and send invitations</p>
            </div>
            <x-black-button href="{{ route('client.team.create') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Invite Team Member
            </x-black-button>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Team Members List -->
        @if($teamMembers->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Team Members Yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Start building your team by inviting members via email.</p>
                <x-black-button href="{{ route('client.team.create') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Invite Your First Team Member
                </x-black-button>
            </div>
        @else
            <!-- Team Members Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($teamMembers as $member)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                        <!-- Member Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $member->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            @if($member->status === 'active')
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-medium rounded-full">
                                    Active
                                </span>
                            @elseif($member->status === 'invited')
                                <span class="px-2.5 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 text-xs font-medium rounded-full">
                                    Invited
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full">
                                    {{ ucfirst($member->status) }}
                                </span>
                            @endif
                        </div>

                        <!-- Role -->
                        @if($member->role)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Role:</span> {{ $member->role }}
                                </p>
                            </div>
                        @endif

                        <!-- Projects Count -->
                        <div class="mb-4 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <span>{{ $member->projects->count() }} {{ Str::plural('project', $member->projects->count()) }}</span>
                        </div>

                        <!-- Invitation Info -->
                        @if($member->status === 'invited' && $member->invitation_sent_at)
                            <div class="mb-4 text-xs text-gray-500 dark:text-gray-400">
                                Invited {{ $member->invitation_sent_at->diffForHumans() }}
                            </div>
                        @elseif($member->joined_at)
                            <div class="mb-4 text-xs text-gray-500 dark:text-gray-400">
                                Joined {{ $member->joined_at->diffForHumans() }}
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                            @if($member->status === 'invited')
                                <!-- Resend Invitation -->
                                <form action="{{ route('client.team.resend', $member) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-black dark:bg-black text-white dark:text-white rounded-full hover:bg-gray-800 dark:hover:bg-gray-900 transition-all duration-200 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Resend
                                    </button>
                                </form>
                            @endif

                            <!-- Remove Member -->
                            <form action="{{ route('client.team.destroy', $member) }}"
                                  method="POST"
                                  id="delete-form-{{ $member->id }}"
                                  class="{{ $member->status === 'invited' ? 'flex-1' : 'w-full' }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        @click="$dispatch('open-delete-modal', { id: 'deleteMemberModal', form: document.getElementById('delete-form-{{ $member->id }}') })"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 dark:bg-red-600 text-white dark:text-white rounded-full hover:bg-red-700 dark:hover:bg-red-700 transition-all duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($teamMembers->hasPages())
                <div class="mt-8">
                    {{ $teamMembers->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <x-delete-modal id="deleteMemberModal">
        <x-slot name="title">Remove Team Member</x-slot>
        <x-slot name="message">
            Are you sure you want to remove this team member? They will lose access to all assigned projects and will need to be re-invited if you change your mind.
        </x-slot>
    </x-delete-modal>
</x-client-app-layout>
