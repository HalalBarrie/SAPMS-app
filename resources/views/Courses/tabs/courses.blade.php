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

<!-- Course List -->
@if($allCourses->isEmpty())
    <div class="text-center py-12 bg-gray-800/50 rounded-xl">
        <div class="text-6xl mb-4">📚</div>
        <h3 class="text-xl font-semibold mb-2 text-white">No courses yet</h3>
        <p class="text-gray-400">Add your first course using the form above to get started!</p>
    </div>
@else
    <div class="space-y-6">
        @foreach($groupedCourses as $academicYear => $semesters)
            <div class="course-year-card bg-gray-800/50 border border-gray-700 rounded-xl overflow-hidden" x-data="{ expanded: {{ $academicYear === $currentYear ? 'true' : 'false' }} }">
                <div class="course-year-header p-4 cursor-pointer" @click="expanded = !expanded">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white">
                            {{ $academicYear }} Academic Year
                        </h3>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('courses.download', ['academic_year' => $academicYear, 'semester' => 'all']) }}" class="action-btn bg-indigo-600 hover:bg-indigo-700 text-white text-xs">Download Year</a>
                            <span class="text-sm text-gray-400">Year GPA: {{ number_format(App\Helpers\GPAHelper::calculateGPA($semesters->flatten(1)), 2) }}</span>
                            <svg class="w-5 h-5 transform transition-transform duration-300 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div x-show="expanded" x-transition class="course-semesters-container p-4 pt-0">
                    @foreach($semesters as $semester => $semesterCourses)
                        <div class="semester-section mb-4 last:mb-0">
                            <div class="semester-header flex justify-between items-center mb-2 p-2 bg-gray-700/50 rounded-md">
                                <h4 class="text-md font-semibold text-gray-300">Semester {{ $semester }}</h4>
                                <div class="flex items-center gap-4">
                                    <span class="px-2 py-1 bg-blue-600 text-white rounded-full text-xs font-medium">
                                        Semester GPA: {{ number_format(App\Helpers\GPAHelper::calculateGPA($semesterCourses), 2) }}
                                    </span>
                                    <a href="{{ route('courses.download', ['academic_year' => $academicYear, 'semester' => $semester]) }}" class="action-btn bg-gray-600 hover:bg-gray-500 text-white text-xs">Download</a>
                                </div>
                            </div>
                            <div class="course-items-container space-y-2">
                                @foreach($semesterCourses as $course)
                                    <div class="course-item bg-gray-900/50 p-3 rounded-lg" data-course-id="{{ $course->id }}">
                                        <div class="course-item-content flex justify-between items-center gap-4">
                                            <div class="course-info flex-grow">
                                                <h5 class="font-semibold text-white">{{ $course->name }}</h5>
                                                <p class="text-sm text-gray-400">{{ $course->code }}</p>
                                            </div>
                                            <div class="course-details flex items-center gap-3">
                                                @php
                                                    $gradeColors = ['A' => 'grade-a', 'B' => 'grade-b', 'C' => 'grade-c', 'D' => 'grade-d', 'E' => 'grade-e', 'F' => 'grade-f'];
                                                @endphp
                                                <span class="grade-badge {{ $gradeColors[$course->grade] ?? 'grade-f' }} px-2 py-1 text-xs rounded-full font-semibold">{{ $course->grade }}</span>
                                                <span class="credits-badge bg-gray-700 text-gray-300 px-2 py-1 text-xs rounded-full">{{ $course->credit_hours }} credits</span>
                                            </div>
                                            <div class="course-actions flex gap-2">
                                                <a href="{{ route('courses.edit', $course) }}" class="action-btn edit-btn">Edit</a>
                                                <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="action-btn delete-btn">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif
