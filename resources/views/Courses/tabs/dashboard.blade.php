{{-- Dashboard Tab Content --}}
<div x-show="activeTab === 'dashboard'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <!-- Performance Overview Cards - Magic Bento Style -->
    <div class="bento-section">
        <div class="bento-grid">
            <div class="bento-card text-white">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Performance</span>
                        <span class="text-2xl">📊</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-1 text-indigo-400">Current GPA</h3>
                        <p class="text-4xl font-bold text-white">{{ number_format($currentYearGPA['cumulative'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bento-card text-white">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Courses</span>
                        <span class="text-2xl">📚</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-1 text-green-400">Total Courses</h3>
                        <p class="text-4xl font-bold text-white">{{ $groupedCourses->flatten(2)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bento-card text-white">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Academic</span>
                        <span class="text-2xl">🎓</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-1 text-purple-400">Academic Years</h3>
                        <p class="text-4xl font-bold text-white">{{ $groupedCourses->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bento-card text-white">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Goals</span>
                        <span class="text-2xl">🎯</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-1 text-orange-400">Goal Progress</h3>
                        <p class="text-4xl font-bold text-white">75%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Year GPA Summary - Magic Bento Style -->
    @if($currentYear)
    <div class="bento-section mb-8">
        <div class="bento-grid">
            <div class="bento-card text-white" style="grid-column: span 3;">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">📊</span>
                            <h2 class="text-xl font-bold text-white">{{ $currentYear }} Academic Year Summary</h2>
                        </div>
                        <button @click="showGPAProjection = !showGPAProjection"
                            class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-colors text-sm">
                            <span x-show="!showGPAProjection">🔮 GPA Projection</span>
                            <span x-show="showGPAProjection">📊 Hide Projection</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white/20 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="text-lg mr-2">📅</span>
                                <div class="text-sm opacity-90">Semester 1 GPA</div>
                            </div>
                            <div class="text-3xl font-bold text-indigo-300">{{ number_format($currentYearGPA['semester1'], 2) }}</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="text-lg mr-2">📅</span>
                                <div class="text-sm opacity-90">Semester 2 GPA</div>
                            </div>
                            <div class="text-3xl font-bold text-green-300">{{ number_format($currentYearGPA['semester2'], 2) }}</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="text-lg mr-2">🏆</span>
                                <div class="text-sm opacity-90">Cumulative GPA</div>
                            </div>
                            <div class="text-3xl font-bold text-purple-300">{{ number_format($currentYearGPA['cumulative'], 2) }}</div>
                        </div>
                    </div>

    <!-- GPA Projection Tool - Magic Bento Style -->
    <div x-show="showGPAProjection" x-transition class="mt-6 pt-6 border-t border-white/20">
        <div class="bento-section">
            <div class="bento-grid">
                <div class="bento-card text-white" style="grid-column: span 2;">
                    <div class="flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-purple-300">Calculator</span>
                            <span class="text-2xl">🔮</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-white">GPA Projection Calculator</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-gray-300">Projected Grade</label>
                                    <select id="projectedGrade" class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white">
                                        <option value="">Select Grade</option>
                                        <option value="5">A (5.0)</option>
                                        <option value="4">B (4.0)</option>
                                        <option value="3">C (3.0)</option>
                                        <option value="2">D (2.0)</option>
                                        <option value="1">E (1.0)</option>
                                        <option value="0">F (0.0)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-gray-300">Credit Hours</label>
                                    <select id="projectedCredits" class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white">
                                        <option value="">Select Credits</option>
                                        <option value="2">2 Credits</option>
                                        <option value="3">3 Credits</option>
                                        <option value="4">4 Credits</option>
                                        <option value="5">5 Credits</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4 p-4 bg-white/10 rounded-lg">
                                <p class="text-sm text-gray-300">Projected GPA: <span id="projectedGPA" class="font-bold text-white">--</span></p>
                                <p class="text-xs opacity-75 mt-1 text-gray-400">Based on Njala University grading system</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions - Magic Bento Style -->
    <div class="bento-section">
        <div class="bento-grid">
            <div class="bento-card text-white cursor-pointer" @click="activeTab = 'courses'">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Quick Action</span>
                        <span class="text-2xl">➕</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Add New Course</h3>
                        <p class="text-sm text-gray-300 opacity-90">Record new course grades and track your progress</p>
                    </div>
                </div>
            </div>

            <div class="bento-card text-white cursor-pointer" @click="activeTab = 'analytics'">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Analytics</span>
                        <span class="text-2xl">📈</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">View Analytics</h3>
                        <p class="text-sm text-gray-300 opacity-90">Check performance trends and insights</p>
                    </div>
                </div>
            </div>

            <div class="bento-card text-white cursor-pointer" @click="activeTab = 'goals'">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Goals</span>
                        <span class="text-2xl">🎯</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Set Goals</h3>
                        <p class="text-sm text-gray-300 opacity-90">Track academic targets and milestones</p>
                    </div>
                </div>
            </div>

            <div class="bento-card text-white cursor-pointer" @click="activeTab = 'settings'">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Settings</span>
                        <span class="text-2xl">⚙️</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Preferences</h3>
                        <p class="text-sm text-gray-300 opacity-90">Customize your experience</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
