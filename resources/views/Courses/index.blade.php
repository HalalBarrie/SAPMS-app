
<!DOCTYPE html>
<html lang="en" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Academic Progress Monitoring System</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-screen" x-data="{ activeTab: 'dashboard', darkMode: true, showHistorical: false, expandedYears: {}, showGPAProjection: false }" x-bind:class="{ 'dark': darkMode }">

    <!-- Header with Navigation -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    📚 SAPMS - Njala University
                </h1>
                <div class="flex items-center space-x-4">
        <button @click="darkMode = !darkMode" 
                        class="px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                        <span x-show="!darkMode">🌙</span>
                        <span x-show="darkMode">☀️</span>
                    </button>
                    @auth
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Welcome, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm transition-colors">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Navigation Tabs -->
            <div class="flex space-x-1 pb-4">
                <button @click="activeTab = 'dashboard'" 
                    :class="activeTab === 'dashboard' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Dashboard
                </button>
                <button @click="activeTab = 'courses'" 
                    :class="activeTab === 'courses' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Courses
                </button>
                <button @click="activeTab = 'analytics'" 
                    :class="activeTab === 'analytics' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Analytics
                </button>
                <button @click="activeTab = 'goals'" 
                    :class="activeTab === 'goals' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Goals
                </button>
                <button @click="activeTab = 'settings'" 
                    :class="activeTab === 'settings' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                    Settings
        </button>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8 space-y-8">
        <!-- Debug Info -->
        <div class="text-sm text-gray-500 mb-4">
            Active Tab: <span x-text="activeTab"></span> | 
            Dark Mode: <span x-text="darkMode"></span> | 
            Show Historical: <span x-text="showHistorical"></span> |
            Courses Count: {{ $groupedCourses->count() }}
        </div>

        <!-- Dashboard Tab -->
        @include('Courses.tabs.dashboard')

        <!-- Courses Tab -->
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

        <!-- Analytics Tab -->
        @include('Courses.tabs.analytics')

        <!-- Goals Tab -->
        @include('Courses.tabs.goals')

        <!-- Settings Tab -->
        @include('Courses.tabs.settings')

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        /* Magic Bento Styles */
        .bento-section {
            --glow-x: 50%;
            --glow-y: 50%;
            --glow-intensity: 0;
            --glow-radius: 200px;
            --glow-color: 132, 0, 255;
            --border-color: #392e4e;
            --background-dark: #060010;
        }
        
        .card--border-glow::after {
            content: '';
            position: absolute;
            inset: 0;
            padding: 6px;
            background: radial-gradient(var(--glow-radius) circle at var(--glow-x) var(--glow-y),
                rgba(132, 0, 255, calc(var(--glow-intensity) * 0.8)) 0%,
                rgba(132, 0, 255, calc(var(--glow-intensity) * 0.4)) 30%,
                transparent 60%);
            border-radius: inherit;
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: subtract;
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1;
        }
        
        .card--border-glow:hover::after {
            opacity: 1;
        }
        
        .card--border-glow:hover {
            box-shadow: 0 4px 20px rgba(46, 24, 78, 0.4), 0 0 30px rgba(132, 0, 255, 0.2);
        }
        
        .global-spotlight {
            position: fixed;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle,
                rgba(132, 0, 255, 0.15) 0%,
                rgba(132, 0, 255, 0.08) 15%,
                rgba(132, 0, 255, 0.04) 25%,
                rgba(132, 0, 255, 0.02) 40%,
                rgba(132, 0, 255, 0.01) 65%,
                transparent 70%
            );
            z-index: 200;
            opacity: 0;
            transform: translate(-50%, -50%);
            mix-blend-mode: screen;
        }
        
        .bento-card {
            background: var(--background-dark);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            transform-style: preserve-3d;
            min-height: 200px;
            display: flex;
            flex-direction: column;
        }
        
        .bento-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .bento-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        
        @media (min-width: 1024px) {
            .bento-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .bento-grid .bento-card:nth-child(3) {
                grid-column: span 2;
                grid-row: span 2;
            }
            
            .bento-grid .bento-card:nth-child(4) {
                grid-column: 1 / span 2;
                grid-row: 2 / span 2;
            }
            
            .bento-grid .bento-card:nth-child(6) {
                grid-column: 4;
                grid-row: 3;
            }
        }
        
        /* AnimatedList Styles */
        .animated-list-container {
            position: relative;
            width: 100%;
        }
        
        .animated-list-wrapper {
            position: relative;
            max-height: 600px;
            overflow: hidden;
            border-radius: 12px;
            background: #060010;
        }
        
        .animated-list-scroll {
            max-height: 600px;
            overflow-y: auto;
            padding: 1rem;
            scrollbar-width: thin;
            scrollbar-color: #222 #060010;
        }
        
        .animated-list-scroll::-webkit-scrollbar {
            width: 8px;
        }
        
        .animated-list-scroll::-webkit-scrollbar-track {
            background: #060010;
        }
        
        .animated-list-scroll::-webkit-scrollbar-thumb {
            background: #222;
            border-radius: 4px;
        }
        
        .animated-list-item {
            margin-bottom: 1rem;
            opacity: 0;
            transform: scale(0.7);
            transition: all 0.3s ease;
        }
        
        .animated-list-item.visible {
            opacity: 1;
            transform: scale(1);
        }
        
        .course-year-card {
            background: #111;
            border: 1px solid #222;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .course-year-card:hover {
            border-color: #333;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        .course-year-header {
            background: #1a1a1a;
            padding: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .course-year-header:hover {
            background: #222;
        }
        
        .course-semesters-container {
            background: #0a0a0a;
            padding: 1rem;
        }
        
        .semester-section {
            margin-bottom: 1.5rem;
        }
        
        .semester-section:last-child {
            margin-bottom: 0;
        }
        
        .semester-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: #111;
            border-radius: 8px;
        }
        
        .course-items-container {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .course-item {
            background: #111;
            border: 1px solid #222;
            border-radius: 8px;
            padding: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .course-item:hover {
            background: #1a1a1a;
            border-color: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .course-item-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        
        .course-info {
            flex: 1;
        }
        
        .course-name {
            font-weight: 600;
            color: white;
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
        }
        
        .course-code {
            color: #888;
            margin: 0;
            font-size: 0.875rem;
        }
        
        .course-details {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .grade-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .grade-a { background: #10b981; color: white; }
        .grade-b { background: #3b82f6; color: white; }
        .grade-c { background: #f59e0b; color: white; }
        .grade-d { background: #f97316; color: white; }
        .grade-e { background: #ef4444; color: white; }
        .grade-f { background: #6b7280; color: white; }
        
        .credits-badge {
            padding: 0.25rem 0.75rem;
            background: #333;
            color: #ccc;
            border-radius: 20px;
            font-size: 0.875rem;
        }
        
        .course-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        
        .edit-btn {
            background: #3b82f6;
            color: white;
        }
        
        .edit-btn:hover {
            background: #2563eb;
        }
        
        .delete-btn {
            background: #ef4444;
            color: white;
        }
        
        .delete-btn:hover {
            background: #dc2626;
        }
        
        .gradient-overlay {
            position: absolute;
            left: 0;
            right: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .gradient-top {
            top: 0;
            height: 50px;
            background: linear-gradient(to bottom, #060010, transparent);
            opacity: 0;
        }
        
        .gradient-bottom {
            bottom: 0;
            height: 100px;
            background: linear-gradient(to top, #060010, transparent);
            opacity: 1;
        }
    </style>
    <script>
        // Initialize Magic Bento
        function initMagicBento() {
            console.log('Initializing Magic Bento...');
            const containers = document.querySelectorAll('.bento-section');
            console.log('Found', containers.length, 'bento sections');
            if (containers.length === 0) {
                console.log('No bento sections found');
                return;
            }
            
            // Create global spotlight if it doesn't exist
            let spotlight = document.querySelector('.global-spotlight');
            if (!spotlight) {
                spotlight = document.createElement("div");
                spotlight.className = "global-spotlight";
                document.body.appendChild(spotlight);
            }
            
                        containers.forEach(container => {
                const cards = container.querySelectorAll('.bento-card');
                console.log('Found', cards.length, 'bento cards in container');
                cards.forEach(card => {
                    card.classList.add('card--border-glow');
                    console.log('Added card--border-glow to card');
                
                // Add event listeners for tilt and magnetism
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    // Tilt effect
                    const rotateX = ((y - centerY) / centerY) * -10;
                    const rotateY = ((x - centerX) / centerX) * 10;
                    
                    gsap.to(card, {
                        rotateX,
                        rotateY,
                        duration: 0.1,
                        ease: "power2.out",
                        transformPerspective: 1000,
                    });
                    
                    // Magnetism effect
                    const magnetX = (x - centerX) * 0.05;
                    const magnetY = (y - centerY) * 0.05;
                    
                    gsap.to(card, {
                        x: magnetX,
                        y: magnetY,
                        duration: 0.3,
                        ease: "power2.out",
                    });
                    
                    // Update glow properties
                    const relativeX = (x / rect.width) * 100;
                    const relativeY = (y / rect.height) * 100;
                    card.style.setProperty("--glow-x", `${relativeX}%`);
                    card.style.setProperty("--glow-y", `${relativeY}%`);
                    card.style.setProperty("--glow-intensity", "1");
                });
                
                card.addEventListener('mouseleave', () => {
                    gsap.to(card, {
                        rotateX: 0,
                        rotateY: 0,
                        x: 0,
                        y: 0,
                        duration: 0.3,
                        ease: "power2.out",
                    });
                    card.style.setProperty("--glow-intensity", "0");
                });
                
                card.addEventListener('click', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const maxDistance = Math.max(
                        Math.hypot(x, y),
                        Math.hypot(x - rect.width, y),
                        Math.hypot(x, y - rect.height),
                        Math.hypot(x - rect.width, y - rect.height)
                    );
                    
                    const ripple = document.createElement("div");
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${maxDistance * 2}px;
                        height: ${maxDistance * 2}px;
                        border-radius: 50%;
                        background: radial-gradient(circle, rgba(132, 0, 255, 0.4) 0%, rgba(132, 0, 255, 0.2) 30%, transparent 70%);
                        left: ${x - maxDistance}px;
                        top: ${y - maxDistance}px;
                        pointer-events: none;
                        z-index: 1000;
                    `;
                    
                    card.appendChild(ripple);
                    
                    gsap.fromTo(
                        ripple,
                        { scale: 0, opacity: 1 },
                        {
                            scale: 1,
                            opacity: 0,
                            duration: 0.8,
                            ease: "power2.out",
                            onComplete: () => ripple.remove(),
                        }
                    );
                });
            });
        });
            
        // Global spotlight effect
        document.addEventListener('mousemove', (e) => {
            const containers = document.querySelectorAll('.bento-section');
            let mouseInsideAny = false;
            
            containers.forEach(container => {
                const rect = container.getBoundingClientRect();
                const mouseInside = 
                    e.clientX >= rect.left &&
                    e.clientX <= rect.right &&
                    e.clientY >= rect.top &&
                    e.clientY <= rect.bottom;
                
                if (mouseInside) {
                    mouseInsideAny = true;
                }
            });
            
            const spotlight = document.querySelector('.global-spotlight');
            if (spotlight) {
                if (mouseInsideAny) {
                    gsap.to(spotlight, {
                        left: e.clientX,
                        top: e.clientY,
                        opacity: 0.8,
                        duration: 0.1,
                        ease: "power2.out",
                    });
                } else {
                    gsap.to(spotlight, {
                        opacity: 0,
                        duration: 0.3,
                        ease: "power2.out",
                    });
                }
            }
        });
    }
        
        // Initialize AnimatedList
        function initAnimatedList() {
            const scrollContainer = document.getElementById('courseListScroll');
            const gradientTop = document.getElementById('gradientTop');
            const gradientBottom = document.getElementById('gradientBottom');
            
            if (!scrollContainer) return;
            
            // Handle scroll for gradient overlays
            const handleScroll = () => {
                const { scrollTop, scrollHeight, clientHeight } = scrollContainer;
                const topOpacity = Math.min(scrollTop / 50, 1);
                const bottomDistance = scrollHeight - (scrollTop + clientHeight);
                const bottomOpacity = scrollHeight <= clientHeight ? 0 : Math.min(bottomDistance / 50, 1);
                
                if (gradientTop) gradientTop.style.opacity = topOpacity;
                if (gradientBottom) gradientBottom.style.opacity = bottomOpacity;
            };
            
            scrollContainer.addEventListener('scroll', handleScroll);
            
            // Animate items on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);
            
            const items = scrollContainer.querySelectorAll('.animated-list-item');
            items.forEach((item, index) => {
                item.style.transitionDelay = `${index * 0.1}s`;
                observer.observe(item);
            });
            
            // Keyboard navigation
            let selectedIndex = -1;
            const handleKeyDown = (e) => {
                if (e.key === 'ArrowDown' || (e.key === 'Tab' && !e.shiftKey)) {
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    updateSelection();
                } else if (e.key === 'ArrowUp' || (e.key === 'Tab' && e.shiftKey)) {
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, 0);
                    updateSelection();
                } else if (e.key === 'Enter') {
                    if (selectedIndex >= 0 && selectedIndex < items.length) {
                        e.preventDefault();
                        const selectedItem = items[selectedIndex];
                        const header = selectedItem.querySelector('.course-year-header');
                        if (header) header.click();
                    }
                }
            };
            
            const updateSelection = () => {
                items.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.style.border = '2px solid #3b82f6';
                        item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        item.style.border = 'none';
                    }
                });
            };
            
            document.addEventListener('keydown', handleKeyDown);
            
            // Mouse hover effects
            items.forEach((item) => {
                item.addEventListener('mouseenter', () => {
                    selectedIndex = Array.from(items).indexOf(item);
                    updateSelection();
                });
            });
        }
        
        // GPA Projection Calculator
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing Magic Bento and AnimatedList');
            
            // Test Alpine.js
            setTimeout(() => {
                console.log('Testing Alpine.js...');
                const body = document.body;
                if (body._x_dataStack && body._x_dataStack[0]) {
                    console.log('Alpine.js data found:', body._x_dataStack[0]);
                } else {
                    console.error('Alpine.js data not found!');
                }
            }, 200);
            
            // Check if GSAP is loaded
            if (typeof gsap === 'undefined') {
                console.error('GSAP is not loaded!');
                return;
            }
            console.log('GSAP is loaded successfully');
            
            // Wait a bit for Alpine.js to initialize
            setTimeout(() => {
                // Initialize Magic Bento
                initMagicBento();
                
                // Initialize AnimatedList
                initAnimatedList();
                
                console.log('Initialization complete');
            }, 100);
            const projectedGrade = document.getElementById('projectedGrade');
            const projectedCredits = document.getElementById('projectedCredits');
            const projectedGPA = document.getElementById('projectedGPA');
            
            function calculateProjectedGPA() {
                const grade = parseFloat(projectedGrade.value);
                const credits = parseFloat(projectedCredits.value);
                
                if (grade && credits) {
                    // Get current total points and credits
                    const currentGPA = {{ $currentYearGPA['cumulative'] ?? 0 }};
                    const currentCredits = {{ $groupedCourses->flatten(2)->sum('credit_hours') ?? 0 }};
                    
                    const currentPoints = currentGPA * currentCredits;
                    const newPoints = grade * credits;
                    const totalPoints = currentPoints + newPoints;
                    const totalCredits = currentCredits + credits;
                    
                    const projectedGPAValue = totalPoints / totalCredits;
                    projectedGPA.textContent = projectedGPAValue.toFixed(2);
                } else {
                    projectedGPA.textContent = '--';
                }
            }
            
            projectedGrade.addEventListener('change', calculateProjectedGPA);
            projectedCredits.addEventListener('change', calculateProjectedGPA);

            // Initialize Charts when Analytics tab is shown
            const analyticsTab = document.querySelector('[x-show="activeTab === \'analytics\'"]');
            if (analyticsTab) {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                            const isVisible = analyticsTab.style.display !== 'none';
                            if (isVisible) {
                                initializeCharts();
                            }
                        }
                    });
                });
                
                observer.observe(analyticsTab, { attributes: true });
            }

            function initializeCharts() {
                // GPA Trend Chart
                const gpaCtx = document.getElementById('gpaChart');
                if (gpaCtx && !gpaCtx.chart) {
                    const gpaData = {
                        labels: {!! json_encode($groupedCourses->keys()->toArray()) !!},
                        datasets: [{
                            label: 'Cumulative GPA',
                            data: {!! json_encode($groupedCourses->map(function($semesters) {
                                $yearCourses = $semesters->flatten(1);
                                return \App\Helpers\GPAHelper::calculateGPA($yearCourses);
                            })->values()->toArray()) !!},
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    };

                    gpaCtx.chart = new Chart(gpaCtx, {
                        type: 'line',
                        data: gpaData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 5,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }

                // Grade Distribution Chart
                const gradeCtx = document.getElementById('gradeChart');
                if (gradeCtx && !gradeCtx.chart) {
                    @php
                        $allCourses = $groupedCourses->flatten(2);
                        $gradeCounts = $allCourses->groupBy('grade')->map->count();
                        $gradeLabels = ['A', 'B', 'C', 'D', 'E', 'F'];
                        $gradeData = [];
                        foreach ($gradeLabels as $grade) {
                            $gradeData[] = $gradeCounts[$grade] ?? 0;
                        }
                    @endphp

                    const gradeData = {
                        labels: {!! json_encode($gradeLabels) !!},
                        datasets: [{
                            data: {!! json_encode($gradeData) !!},
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.8)',   // Green for A
                                'rgba(59, 130, 246, 0.8)',  // Blue for B
                                'rgba(234, 179, 8, 0.8)',   // Yellow for C
                                'rgba(249, 115, 22, 0.8)',  // Orange for D
                                'rgba(239, 68, 68, 0.8)',   // Red for E
                                'rgba(107, 114, 128, 0.8)'  // Gray for F
                            ],
                            borderColor: [
                                'rgb(34, 197, 94)',
                                'rgb(59, 130, 246)',
                                'rgb(234, 179, 8)',
                                'rgb(249, 115, 22)',
                                'rgb(239, 68, 68)',
                                'rgb(107, 114, 128)'
                            ],
                            borderWidth: 2
                        }]
                    };

                    gradeCtx.chart = new Chart(gradeCtx, {
                        type: 'doughnut',
                        data: gradeData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Initialize charts immediately if analytics tab is visible
            if (document.querySelector('[x-show="activeTab === \'analytics\'"]').style.display !== 'none') {
                initializeCharts();
            }
        });
    </script>
</body>
</html>
