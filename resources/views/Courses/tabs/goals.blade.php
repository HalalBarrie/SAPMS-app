{{-- Goals Tab Content --}}
<div x-show="activeTab === 'goals'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <!-- Test Content -->
    <div class="bg-yellow-500 text-white p-4 rounded-lg mb-4">
        <h3>🎯 GOALS TAB IS WORKING!</h3>
        <p>If you can see this yellow box, the Goals tab is functioning correctly.</p>
    </div>
    <div class="bento-section">
        <div class="bento-grid">
            <div class="bento-card text-white" style="grid-column: span 2;">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Goals</span>
                        <span class="text-2xl">🎯</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold mb-6 text-white">Academic Goals</h2>
                        <form class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300">Goal Type</label>
                                <select class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white">
                                    <option>Target GPA</option>
                                    <option>Course Completion</option>
                                    <option>Semester Goal</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300">Target Value</label>
                                <input type="text" placeholder="e.g., 3.5 GPA" class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300">Deadline</label>
                                <input type="date" class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white">
                            </div>
                            <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                                Set Goal
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bento-card text-white" style="grid-column: span 2;">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Current Goals</span>
                        <span class="text-2xl">📋</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-white">Current Goals</h3>
                        <div class="space-y-4">
                            <div class="p-4 bg-green-600/20 rounded-lg border border-green-500/30">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-semibold text-green-300">Target GPA: 3.5</h4>
                                        <p class="text-sm text-green-400">Deadline: Dec 2024</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-green-200">75%</div>
                                        <div class="text-xs text-gray-400">Complete</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
