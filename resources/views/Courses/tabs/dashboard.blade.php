<div>
    @auth
        @if($allCourses->isNotEmpty())
            @php
                $user = Auth::user();
                
                // Determine Current Academic Level
                $level = "Year/Semester not set in profile";
                if ($currentYear && $currentSemester) {
                    // Attempt to determine year number relative to the first course entered.
                    $firstCourseYear = (int)substr($allCourses->first()->academic_year, 0, 4);
                    $currentAcademicYearStart = (int)substr($currentYear, 0, 4);
                    $year = $currentAcademicYearStart - $firstCourseYear + 1;
                    $level = "Year {$year} - " . ($currentSemester == 1 ? '1st' : '2nd') . " Semester ({$currentYear})";
                }

                // Find Goals
                $semesterGoal = $goals->firstWhere('type', 'semester');
                $cumulativeGoal = $goals->firstWhere('type', 'cumulative');

                // Find Highlight Courses for the current semester
                $currentSemesterCourses = $allCourses->where('academic_year', $currentYear)->where('semester', $currentSemester);
                $bestCourse = $currentSemesterCourses->sortBy('grade')->first();
                $worstCourse = $currentSemesterCourses->sortByDesc('grade')->first();

            @endphp

            <div class="bento-grid" style="grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                <!-- Welcome & Level -->
                <div class="bento-card" style="grid-column: span 4;">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Welcome, {{ $user->name }}</h2>
                            <p class="text-indigo-300 text-lg">{{ $level }}</p>
                        </div>
                        <div>
                            <a href="{{ route('courses.download.all') }}" class="action-btn bg-purple-600 hover:bg-purple-700 text-white">Download Full Report</a>
                        </div>
                    </div>
                </div>

                <!-- Core Stats -->
                <div class="bento-card" style="grid-column: span 1;">
                    <h3 class="text-sm font-medium text-gray-400">Current Semester GPA</h3>
                    <p class="mt-1 text-4xl font-semibold text-white">{{ number_format($currentSemesterGPA, 2) }}</p>
                </div>
                <div class="bento-card" style="grid-column: span 1;">
                    <h3 class="text-sm font-medium text-gray-400">Cumulative GPA</h3>
                    <p class="mt-1 text-4xl font-semibold text-white">{{ number_format($cumulativeGPA, 2) }}</p>
                </div>
                <div class="bento-card" style="grid-column: span 1;">
                    <h3 class="text-sm font-medium text-gray-400">Total Credits</h3>
                    <p class="mt-1 text-4xl font-semibold text-white">{{ $totalCredits }}</p>
                </div>
                <div class="bento-card" style="grid-column: span 1;">
                    <h3 class="text-sm font-medium text-gray-400">Courses Logged</h3>
                    <p class="mt-1 text-4xl font-semibold text-white">{{ $allCourses->count() }}</p>
                </div>

                <!-- Goal Progress -->
                <div class="bento-card" style="grid-column: span 2; grid-row: span 2;">
                    <h3 class="text-lg font-bold text-white mb-4">Goal Progress</h3>
                    <div class="space-y-6 flex flex-col justify-center h-full">
                        @if($semesterGoal)
                            @php $semesterProgress = ($semesterGoal->target_gpa > 0) ? ($currentSemesterGPA / $semesterGoal->target_gpa) * 100 : 0; @endphp
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-gray-300">Semester Goal</span>
                                    <span class="text-indigo-300 font-bold">{{ number_format($currentSemesterGPA, 2) }} / {{ number_format($semesterGoal->target_gpa, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-indigo-500 h-2.5 rounded-full" style="width: {{ min($semesterProgress, 100) }}%"></div>
                                </div>
                            </div>
                        @endif
                        @if($cumulativeGoal)
                             @php $cumulativeProgress = ($cumulativeGoal->target_gpa > 0 && is_numeric($cumulativeGPA)) ? ($cumulativeGPA / $cumulativeGoal->target_gpa) * 100 : 0; @endphp
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-gray-300">Cumulative Goal</span>
                                    <span class="text-green-300 font-bold">{{ is_numeric($cumulativeGPA) ? number_format($cumulativeGPA, 2) : '0.00' }} / {{ number_format($cumulativeGoal->target_gpa, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ min($cumulativeProgress, 100) }}%"></div>
                                </div>
                            </div>
                        @endif
                        @if(!$semesterGoal && !$cumulativeGoal)
                            <div class="text-center">
                                <p class="text-gray-400">No goals set yet.</p>
                                <button @click="$store.app.activeTab = 'goals'" class="mt-2 text-indigo-400 hover:text-indigo-300 font-semibold">+ Set a New Goal</button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Highlights -->
                <div class="bento-card" style="grid-column: span 2;">
                    <h3 class="text-lg font-bold text-white mb-4">Current Semester Highlights</h3>
                    <div class="space-y-4">
                        @if($bestCourse)
                            <div class="p-4 bg-gray-900/50 rounded-lg">
                                <p class="text-sm text-green-300">Top Course</p>
                                <p class="text-lg font-semibold text-white">{{ $bestCourse->name }}</p>
                                <p class="text-xl font-bold text-green-400">Grade: {{ $bestCourse->grade }}</p>
                            </div>
                        @endif
                        @if($worstCourse && $bestCourse->id !== $worstCourse->id)
                            <div class="p-4 bg-gray-900/50 rounded-lg">
                                <p class="text-sm text-red-300">Most Challenging</p>
                                <p class="text-lg font-semibold text-white">{{ $worstCourse->name }}</p>
                                <p class="text-xl font-bold text-red-400">Grade: {{ $worstCourse->grade }}</p>
                            </div>
                        @endif
                         @if(!$bestCourse)
                            <p class="text-center text-gray-400 pt-8">No courses logged for this semester yet.</p>
                        @endif
                    </div>
                </div>
            </div>

        @else
            <div class="text-center py-12 bg-gray-800 shadow-lg rounded-xl border border-gray-700">
                <div class="text-6xl mb-4">👋</div>
                <h3 class="text-2xl font-semibold mb-2 text-white">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-400">Your dashboard is ready. Add your first course to see your academic progress.</p>
                <button @click="$store.app.activeTab = 'courses'" class="mt-8 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    + Add Your First Course
                </button>
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-gray-800 shadow-lg rounded-xl border border-gray-700">
            <div class="text-6xl mb-4">📚</div>
            <h3 class="text-2xl font-semibold mb-2 text-white">Welcome to SAPMS</h3>
            <p class="text-gray-400">Log in to manage your courses and track your academic progress.</p>
            <a href="{{ route('login') }}" class="mt-8 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Login to Get Started
            </a>
        </div>
    @endauth
</div>