{{-- Courses Tab Content --}}
<div x-show="activeTab === 'courses'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <!-- Test Content -->
    <div class="bg-blue-500 text-white p-4 rounded-lg mb-4">
        <h3>🎯 COURSES TAB IS WORKING!</h3>
        <p>If you can see this blue box, the Courses tab is functioning correctly.</p>
    </div>
    <!-- Add Course Form Card - Magic Bento Style -->
    <div class="bento-section mb-8">
        <div class="bento-grid">
            <div class="bento-card text-white" style="grid-column: span 4;">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-purple-300">Course Management</span>
                        <span class="text-2xl">📝</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold mb-6 text-center text-white">Add New Course</h2>
                        <form method="POST" action="{{ route('courses.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @csrf
    <input type="text" name="course_name" placeholder="Course Name" required
                                class="p-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300 w-full focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all">

    <input type="text" name="course_code" placeholder="Course Code" required
                                class="p-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300 w-full focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all">

                        <select name="grade" required class="p-3 rounded-lg bg-white/20 border border-white/30 text-white w-full focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all">
    <option value="">Select Grade</option>
                                <option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option>
    </select>

                        <select name="credit_hours" required class="p-3 rounded-lg bg-white/20 border border-white/30 text-white w-full focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all">
                                <option value="">Credit Hours</option>
                                <option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>
    </select>

                        <select name="semester" required class="p-3 rounded-lg bg-white/20 border border-white/30 text-white w-full focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all">
                                <option value="1">1st Semester </option>
                                <option value="2">2nd Semester </option>
    </select>

                        <select name="academic_year" required class="p-3 rounded-lg bg-white/20 border border-white/30 text-white w-full focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all">
    @for ($year = date('Y'); $year >= date('Y') - 10; $year--)
        <option value="{{ $year }}/{{ $year+1 }}">{{ $year }}/{{ $year+1 }}</option>
    @endfor
    </select>

    <button type="submit"
                                class="col-span-full mt-4 py-3 px-6 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-semibold shadow-md hover:shadow-lg transition-all">
                                Add Course
    </button>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course List with Collapsible Sections -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">📋 Course Records</h2>
            <button @click="showHistorical = !showHistorical"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                <span x-show="!showHistorical">📚 Show Historical</span>
                <span x-show="showHistorical">📖 Current Year Only</span>
            </button>
        </div>

        @if($groupedCourses->isEmpty())
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">No courses yet</h3>
                <p class="text-gray-600 dark:text-gray-400">Add your first course to get started!</p>
                <p class="text-sm text-gray-500 mt-2">Debug: Grouped courses count: {{ $groupedCourses->count() }}</p>
            </div>
        @else
            <div class="text-sm text-gray-500 mb-4">Debug: Found {{ $groupedCourses->count() }} academic years</div>
            <div class="animated-list-container">
                <div class="animated-list-wrapper">
                    <div class="animated-list-scroll" id="courseListScroll">
                        @foreach($groupedCourses as $academicYear => $semesters)
                            @php
                                $isCurrentYear = $academicYear === $currentYear;
                            @endphp

                            <div x-show="showHistorical || {{ $isCurrentYear ? 'true' : 'false' }}"
                                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95"
                                 class="animated-list-item" data-index="{{ $loop->index }}">
                                <div class="course-year-card">
                                    <div class="course-year-header"
                                         @click="expandedYears.has('{{ $academicYear }}') ? expandedYears.delete('{{ $academicYear }}') : expandedYears.add('{{ $academicYear }}')">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-3">
                                                <h3 class="text-lg font-bold text-white">
                                                    {{ $academicYear }} Academic Year
                                                </h3>
                                                @if($isCurrentYear)
                                                    <span class="px-2 py-1 bg-indigo-600 text-white rounded-full text-xs font-medium">
                                                        Current
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                @php
                                                    $yearCourses = $semesters->flatten(1);
                                                    $yearGPA = \App\Helpers\GPAHelper::calculateGPA($yearCourses);
                                                @endphp
                                                <span class="text-sm font-semibold text-gray-300">
                                                    GPA: {{ number_format($yearGPA, 2) }}
                                                </span>
                                                <svg class="w-5 h-5 transform transition-transform duration-300 text-gray-400"
                                                     :class="expandedYears.has('{{ $academicYear }}') ? 'rotate-180' : ''"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="expandedYears.has('{{ $academicYear }}')" x-transition class="course-semesters-container">
                                        @foreach([1, 2] as $semester)
                                            @php
                                                $semesterCourses = $semesters[$semester] ?? collect();
                                                $semesterGPA = \App\Helpers\GPAHelper::calculateGPA($semesterCourses);
                                            @endphp

                                            <div class="semester-section">
                                                <div class="semester-header">
                                                    <h4 class="text-md font-semibold text-gray-300">
                                                        Semester {{ $semester }}
                                                    </h4>
                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-sm text-gray-400">
                                                            {{ $semesterCourses->count() }} courses
                                                        </span>
                                                        <span class="px-2 py-1 bg-blue-600 text-white rounded-full text-sm font-medium">
                                                            GPA: {{ number_format($semesterGPA, 2) }}
                                                                                                                </span>
                                            </div>
</div>

                                                @if($semesterCourses->isEmpty())
                                                            <div class="text-center text-gray-400 py-4">
                                                                No courses in Semester {{ $semester }}
                                                            </div>
                                                        @else
                                                            <div class="course-items-container">
                                                                @foreach($semesterCourses as $course)
                                                                    <div class="course-item" data-course-id="{{ $course->id }}">
                                                                        <div class="course-item-content">
                                                                            <div class="course-info">
                                                                                <h5 class="course-name">{{ $course->course_name }}</h5>
                                                                                <p class="course-code">{{ $course->course_code }}</p>
                                                                            </div>
                                                                            <div class="course-details">
                                                                                @php
                                                                                    $gradeColors = [
                                                                                        'A' => 'grade-a',
                                                                                        'B' => 'grade-b',
                                                                                        'C' => 'grade-c',
                                                                                        'D' => 'grade-d',
                                                                                        'E' => 'grade-e',
                                                                                        'F' => 'grade-f'
                                                                                    ];
                                                                                @endphp
                                                                                <span class="grade-badge {{ $gradeColors[$course->grade] ?? 'grade-f' }}">
                                                                                    {{ $course->grade }}
                                                                                </span>
                                                                                <span class="credits-badge">{{ $course->credit_hours }} credits</span>
                                                                            </div>
                                                                            <div class="course-actions">
                                                                                <a href="{{ route('courses.edit', $course) }}"
                                                                                   class="action-btn edit-btn">Edit</a>
                                                                                <form action="{{ route('courses.destroy', $course) }}" method="POST"
                                                                                      onsubmit="return confirm('Delete this course?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                                                                    <button class="action-btn delete-btn">Delete</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Gradient Overlays -->
                            <div class="gradient-overlay gradient-top" id="gradientTop"></div>
                            <div class="gradient-overlay gradient-bottom" id="gradientBottom"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
