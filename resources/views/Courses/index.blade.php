
<!DOCTYPE html>
<html lang="en" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Academic Progress Monitoring System</title>
    <meta name="description" content="A web application for students to monitor their academic progress by tracking courses, grades, and calculating GPA.">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-screen" x-data x-init="$store.app.init()">

    <!-- Header with Navigation -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    📚 SAPMS - Njala University
                </h1>
                <div class="flex items-center space-x-4">
        <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                            <span x-show="$store.app.theme === 'light'">☀️</span>
                            <span x-show="$store.app.theme === 'dark'">🌙</span>
                            <span x-show="$store.app.theme === 'system'">💻</span>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-10">
                            <a href="#" @click.prevent="$store.app.setTheme('light'); open = false" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Light</a>
                            <a href="#" @click.prevent="$store.app.setTheme('dark'); open = false" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Dark</a>
                            <a href="#" @click.prevent="$store.app.setTheme('system'); open = false" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">System</a>
                        </div>
                    </div>
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
                <button @click="$store.app.activeTab = 'dashboard'" 
                    :class="$store.app.activeTab === 'dashboard' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Dashboard
                </button>
                <button @click="$store.app.activeTab = 'courses'" 
                    :class="$store.app.activeTab === 'courses' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Courses
                </button>
                <button @click="$store.app.activeTab = 'analytics'" 
                    :class="$store.app.activeTab === 'analytics' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Analytics
                </button>
                <button @click="$store.app.activeTab = 'projections'" 
                    :class="$store.app.activeTab === 'projections' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Projections
                </button>
                <button @click="$store.app.activeTab = 'goals'" 
                    :class="$store.app.activeTab === 'goals' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                     Goals
                </button>
                <button @click="$store.app.activeTab = 'settings'" 
                    :class="$store.app.activeTab === 'settings' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                    Settings
        </button>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8 space-y-8">
        <!-- Debug Info -->
        <div class="text-sm text-gray-500 mb-4">
            Active Tab: <span x-text="$store.app.activeTab"></span> | 
            Theme: <span x-text="$store.app.theme"></span> | 
            Show Historical: <span x-text="$store.app.showHistorical"></span> |
            Courses Count: {{ $groupedCourses->count() }}
        </div>

        <!-- Dashboard Tab -->
        <div x-show="$store.app.activeTab === 'dashboard'" x-cloak>
            @include('Courses.tabs.dashboard', [
                'allCourses' => $allCourses,
                'cumulativeGPA' => $cumulativeGPA,
                'currentYear' => $currentYear,
                'currentSemester' => $currentSemester,
                'currentSemesterGPA' => $currentSemesterGPA,
                'totalCredits' => $totalCredits,
                'goals' => $goals
            ])
        </div>

        <!-- Courses Tab -->
        <div x-show="$store.app.activeTab === 'courses'" x-cloak>
            @include('Courses.tabs.courses', ['allCourses' => $allCourses, 'groupedCourses' => $groupedCourses, 'currentYear' => $currentYear])
        </div>

        <!-- Analytics Tab -->
        <div x-show="$store.app.activeTab === 'analytics'" x-cloak>
            @include('Courses.tabs.analytics', [
                'semesters' => $semesterLabels,
                'gpaTrend' => $semesterGpaValues,
                'gradeDistribution' => $gradeDistribution
            ])
        </div>

        <!-- Projections Tab -->
        <div x-show="$store.app.activeTab === 'projections'" x-cloak>
            @include('Courses.tabs.projections', [
                'cumulativeGPA' => $cumulativeGPA,
                'totalCredits' => $totalCredits
            ])
        </div>

        <!-- Goals Tab -->
        <div x-show="$store.app.activeTab === 'goals'" x-cloak>
            @include('Courses.tabs.goals', ['goals' => $goals, 'semesterGPA' => $currentSemesterGPA, 'cumulativeGPA' => $cumulativeGPA])
        </div>

        <!-- Settings Tab -->
        <div x-show="$store.app.activeTab === 'settings'" x-cloak>
            @include('Courses.tabs.settings')
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('app', {
                theme: 'system',
                
                init() {
                    this.theme = localStorage.getItem('theme') || 'system';
                    
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                        if (this.theme === 'system') {
                            this.updateThemeClass();
                        }
                    });

                    this.updateThemeClass();
                },

                isDark() {
                    if (this.theme === 'system') {
                        return window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }
                    return this.theme === 'dark';
                },

                updateThemeClass() {
                    if (this.isDark()) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },

                setTheme(newTheme) {
                    this.theme = newTheme;
                    localStorage.setItem('theme', newTheme);
                    this.updateThemeClass();
                },

                // Keep existing properties
                activeTab: 'dashboard',
                showHistorical: false,
                expandedYears: {},
            });
        });
    </script>
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
                // Ensure all elements are present
                if (!projectedGrade || !projectedCredits || !projectedGPA) {
                    return;
                }

                const grade = parseFloat(projectedGrade.value);
                const credits = parseFloat(projectedCredits.value);

                if (grade >= 0 && credits > 0) {
                    const currentGPA = {{ $cumulativeGPA ?? 0 }};
                    const currentCredits = {{ $totalCredits ?? 0 }};

                    const currentPoints = currentGPA * currentCredits;
                    const newPoints = grade * credits;
                    const totalPoints = currentPoints + newPoints;
                    const totalCredits = currentCredits + credits;

                    if (totalCredits > 0) {
                        const projectedGPAValue = totalPoints / totalCredits;
                        projectedGPA.textContent = projectedGPAValue.toFixed(2);
                    } else {
                        projectedGPA.textContent = '0.00';
                    }
                } else {
                    projectedGPA.textContent = '--';
                }
            }

            // Only add listeners if the calculator elements are on the page
            if (projectedGrade && projectedCredits && projectedGPA) {
                projectedGrade.addEventListener('input', calculateProjectedGPA);
                projectedCredits.addEventListener('input', calculateProjectedGPA);
            }
        });
    </script>
</body>
</html>

