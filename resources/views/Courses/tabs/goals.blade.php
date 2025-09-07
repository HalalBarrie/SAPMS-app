<div x-data="goalsTab()" x-transition>
    @auth
        @php
            $semesterGoal = $goals->firstWhere('type', 'semester');
            $cumulativeGoal = $goals->firstWhere('type', 'cumulative');

            $semesterProgress = 0;
            if ($semesterGoal && $semesterGoal->target_gpa > 0) {
                $semesterProgress = ($semesterGPA / $semesterGoal->target_gpa) * 100;
            }

            $cumulativeProgress = 0;
            if ($cumulativeGoal && $cumulativeGoal->target_gpa > 0 && is_numeric($cumulativeGPA)) {
                $cumulativeProgress = ($cumulativeGPA / $cumulativeGoal->target_gpa) * 100;
            }
        @endphp

        <div class="bento-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <!-- Goal Creation Form -->
            <div class="bento-card" style="grid-column: span 1;">
                <h3 class="text-lg font-bold text-white mb-4">Set a New Goal</h3>
                <form action="{{ route('goals.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="goal_type" class="block text-sm font-medium text-gray-300">Goal Type</label>
                        <select name="type" id="goal_type" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                            <option value="semester">This Semester's GPA</option>
                            <option value="cumulative">Overall Cumulative GPA</option>
                        </select>
                    </div>
                    <div>
                        <label for="target_gpa" class="block text-sm font-medium text-gray-300">Target GPA</label>
                        <input type="number" step="0.01" max="5.00" min="0.00" name="target_gpa" id="target_gpa" required class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">Set Goal</button>
                </form>
            </div>

            <!-- Active Goals -->
            <div class="bento-card" style="grid-column: span 2;">
                <h3 class="text-lg font-bold text-white mb-4">Active Goals</h3>
                <div class="space-y-6">
                    <!-- Semester Goal Display -->
                    @if ($semesterGoal)
                        <div>
                            <div class="flex justify-between items-baseline mb-1">
                                <p class="text-gray-300">Semester Goal <span class="text-xs">({{ $semesterGoal->academic_year }} - S{{$semesterGoal->semester}})</span></p>
                                <form action="{{ route('goals.destroy', $semesterGoal) }}" method="POST" onsubmit="return confirm('Remove this goal?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 text-xs">Remove</button>
                                </form>
                            </div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-2xl font-bold text-indigo-300">{{ number_format($semesterGPA, 2) }}</span>
                                <span class="text-lg text-gray-400">/ {{ number_format($semesterGoal->target_gpa, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-3">
                                <div class="bg-indigo-500 h-3 rounded-full" style="width: {{ min($semesterProgress, 100) }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="text-center p-4 bg-gray-900/50 rounded-lg">
                            <p class="text-gray-400">No active semester goal. Set one now!</p>
                        </div>
                    @endif

                    <!-- Cumulative Goal Display -->
                    @if ($cumulativeGoal)
                        <div>
                             <div class="flex justify-between items-baseline mb-1">
                                <p class="text-gray-300">Cumulative Goal</p>
                                <form action="{{ route('goals.destroy', $cumulativeGoal) }}" method="POST" onsubmit="return confirm('Remove this goal?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 text-xs">Remove</button>
                                </form>
                            </div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-2xl font-bold text-green-300">{{ is_numeric($cumulativeGPA) ? number_format($cumulativeGPA, 2) : '0.00' }}</span>
                                <span class="text-lg text-gray-400">/ {{ number_format($cumulativeGoal->target_gpa, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full" style="width: {{ min($cumulativeProgress, 100) }}%"></div>
                            </div>
                        </div>
                    @else
                         <div class="text-center p-4 bg-gray-900/50 rounded-lg">
                            <p class="text-gray-400">No active cumulative goal. Set one now!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-gray-800 shadow-lg rounded-xl border border-gray-700">
            <div class="text-6xl mb-4">🎯</div>
            <h3 class="text-xl font-semibold mb-2 text-white">Set Your Goals</h3>
            <p class="text-gray-400">Log in to set and track your academic goals.</p>
            <a href="{{ route('login') }}" class="mt-6 inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                Login
            </a>
        </div>
    @endauth

    <style>
        .bento-select option {
            background: #1f2937 !important;
            color: white !important;
        }
    </style>

    <script>
        function goalsTab() {
            return {};
        }
    </script>
</div>
