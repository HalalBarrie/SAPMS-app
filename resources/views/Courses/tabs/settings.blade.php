{{-- Settings Tab Content --}}
<div x-show="activeTab === 'settings'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <!-- Test Content -->
    <div class="bg-purple-500 text-white p-4 rounded-lg mb-4">
        <h3>⚙️ SETTINGS TAB IS WORKING!</h3>
        <p>If you can see this purple box, the Settings tab is functioning correctly.</p>
    </div>
    <div class="bento-section">
        <div class="bento-grid">
            <div class="bento-card text-white" style="grid-column: span 2;">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Profile</span>
                        <span class="text-2xl">👤</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold mb-6 text-white">Settings & Profile</h2>
                        <form class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300">Full Name</label>
                                <input type="text" value="{{ Auth::user()->name ?? '' }}" class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300">Email</label>
                                <input type="email" value="{{ Auth::user()->email ?? '' }}" class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300">Student ID</label>
                                <input type="text" placeholder="Enter your student ID" class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300">
                            </div>
                            <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                                Update Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bento-card text-white" style="grid-column: span 2;">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Preferences</span>
                        <span class="text-2xl">⚙️</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-white">Preferences</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Dark Mode</span>
                                <button @click="darkMode = !darkMode"
                                    :class="darkMode ? 'bg-purple-600' : 'bg-gray-600'"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                                    <span :class="darkMode ? 'translate-x-6' : 'translate-x-1'"
                                          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Email Notifications</span>
                                <input type="checkbox" checked class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Goal Reminders</span>
                                <input type="checkbox" checked class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
