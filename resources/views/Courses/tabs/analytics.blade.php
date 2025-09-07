<div x-data="analyticsTab()" x-transition>
    @auth
        @if($allCourses->isEmpty())
            <div class="text-center py-12 bg-gray-800 shadow-lg rounded-xl border border-gray-700">
                <div class="text-6xl mb-4">📊</div>
                <h3 class="text-xl font-semibold mb-2 text-white">Analytics Unavailable</h3>
                <p class="text-gray-400">You need to add some graded courses to see your analytics.</p>
                <button @click="$store.app.activeTab = 'courses'" class="mt-6 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    + Add a Course
                </button>
            </div>
        @else
            @php
                $semesterPerformances = !empty($semesters) ? array_combine($semesters, $gpaTrend) : [];
                if(!empty($semesterPerformances)) {
                    arsort($semesterPerformances);
                    $bestSemester = key($semesterPerformances);
                    $bestGpa = reset($semesterPerformances);
                    asort($semesterPerformances);
                    $worstSemester = key($semesterPerformances);
                    $worstGpa = reset($semesterPerformances);
                } else {
                    $bestSemester = 'N/A'; $bestGpa = 0;
                    $worstSemester = 'N/A'; $worstGpa = 0;
                }

                // Prepare data for Grade Distribution chart
                $gradeKeys = ['A', 'B', 'C', 'D', 'E', 'F'];
                $colorMap = [
                    'A' => '#47FC5B', 'B' => '#1F53FF', 'C' => '#47FCD9',
                    'D' => '#FFF74F', 'E' => '#FC7447', 'F' => '#FF2E1D',
                ];

                $filteredGrades = collect($gradeKeys)->mapWithKeys(function ($grade) use ($gradeDistribution) {
                    return $gradeDistribution->has($grade) ? [$grade => $gradeDistribution[$grade]] : [];
                });

                $chartLabels = $filteredGrades->keys()->all();
                $chartData = $filteredGrades->values()->all();
                $chartColors = $filteredGrades->keys()->map(fn($grade) => $colorMap[$grade])->all();
            @endphp
            <div class="bento-grid" style="grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                <!-- GPA Trend Chart -->
                <div class="bento-card" style="grid-column: span 4; min-height: 400px;">
                    <h3 class="text-lg font-bold text-white mb-4">GPA Trend Over Time</h3>
                    @if(count($semesters) > 1)
                        <div class="h-full w-full relative">
                            <canvas id="gpaTrendChart"></canvas>
                        </div>
                    @else
                        <div class="h-full w-full flex flex-col items-center justify-center text-center">
                            <p class="text-gray-400">Add courses from at least two different semesters to see your GPA trend.</p>
                        </div>
                    @endif
                </div>

                <!-- Grade Distribution -->
                <div class="bento-card" style="grid-column: span 2; min-height: 350px;">
                    <h3 class="text-lg font-bold text-white mb-4">Grade Distribution</h3>
                    <div class="h-full w-full flex items-center justify-center relative">
                        <canvas id="gradeDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Performance Insights -->
                <div class="bento-card" style="grid-column: span 2;">
                    <h3 class="text-lg font-bold text-white mb-4">Performance Insights</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-900/50 rounded-lg">
                            <p class="text-sm text-green-300">Best Semester</p>
                            <p class="text-xl font-semibold text-white">{{ $bestSemester }}</p>
                            <p class="text-2xl font-bold text-green-400">{{ number_format($bestGpa, 2) }} <span class="text-sm">GPA</span></p>
                        </div>
                        <div class="p-4 bg-gray-900/50 rounded-lg">
                            <p class="text-sm text-red-300">Lowest Semester</p>
                            <p class="text-xl font-semibold text-white">{{ $worstSemester }}</p>
                            <p class="text-2xl font-bold text-red-400">{{ number_format($worstGpa, 2) }} <span class="text-sm">GPA</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function analyticsTab() {
                    return {
                        gpaChartInstance: null,
                        gradeChartInstance: null,
                        init() {
                            this.$watch('$store.app.activeTab', (newTab) => {
                                if (newTab === 'analytics') {
                                    setTimeout(() => this.initializeCharts(), 50);
                                } else {
                                    this.destroyCharts();
                                }
                            });
                            if (this.$store.app.activeTab === 'analytics') {
                                setTimeout(() => this.initializeCharts(), 50);
                            }
                        },
                        destroyCharts() {
                            if (this.gpaChartInstance) this.gpaChartInstance.destroy();
                            if (this.gradeChartInstance) this.gradeChartInstance.destroy();
                            this.gpaChartInstance = null;
                            this.gradeChartInstance = null;
                        },
                        initializeCharts() {
                            if (this.gpaChartInstance) return;
                            const gpaCtx = document.getElementById('gpaTrendChart');
                            if (gpaCtx) {
                                this.gpaChartInstance = new Chart(gpaCtx, {
                                    type: 'line',
                                    data: {
                                        labels: {!! json_encode($semesters) !!},
                                        datasets: [{
                                            label: 'Semester GPA', data: {!! json_encode($gpaTrend) !!},
                                            borderColor: 'rgb(99, 102, 241)', backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                            tension: 0.4, fill: true
                                        }]
                                    },
                                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: false, ticks: { color: '#9ca3af' } }, x: { ticks: { color: '#9ca3af' } } }, plugins: { legend: { display: false } } }
                                });
                            }
                            const gradeCtx = document.getElementById('gradeDistributionChart');
                            if (gradeCtx && {!! json_encode(!empty($chartLabels)) !!}) {
                                this.gradeChartInstance = new Chart(gradeCtx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: {!! json_encode($chartLabels) !!},
                                        datasets: [{
                                            label: 'Number of Courses',
                                            data: {!! json_encode($chartData) !!},
                                            backgroundColor: {!! json_encode($chartColors) !!},
                                            borderColor: '#111827', borderWidth: 4
                                        }]
                                    },
                                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af', usePointStyle: true } } } }
                                });
                            }
                        }
                    }
                }
            </script>
        @endif
    @else
        <div class="text-center py-12 bg-gray-800 shadow-lg rounded-xl border border-gray-700">
            <div class="text-6xl mb-4">📊</div>
            <h3 class="text-xl font-semibold mb-2 text-white">Analytics</h3>
            <p class="text-gray-400">Log in to see your GPA trends and grade distribution.</p>
            <a href="{{ route('login') }}" class="mt-6 inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                Login
            </a>
        </div>
    @endauth
</div>