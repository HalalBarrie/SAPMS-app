<!-- resources/views/Courses/tabs/analytics.blade.php -->
<div x-show="activeTab === 'analytics'" x-transition.opacity.duration.500ms>
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">📊 Analytics</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- GPA Trend Chart -->
            <div class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">GPA Trend</h3>
                <div class="h-64">
                    <canvas id="gpaTrendChart"></canvas>
                </div>
            </div>

            <!-- Grade Distribution Chart -->
            <div class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Grade Distribution</h3>
                <div class="h-64 flex justify-center items-center">
                    <canvas id="gradeDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    let gpaTrendChartInstance = null;
    let gradeDistributionChartInstance = null;

    const initializeCharts = () => {
        // GPA Trend Chart
        const gpaCtx = document.getElementById('gpaTrendChart');
        if (gpaCtx && !gpaTrendChartInstance) {
            gpaTrendChartInstance = new Chart(gpaCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($semesters) !!},
                    datasets: [{
                        label: 'Semester GPA',
                        data: {!! json_encode($gpaTrend) !!},
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: false, max: 5.0 } }
                }
            });
        }

        // Grade Distribution Chart
        const gradeCtx = document.getElementById('gradeDistributionChart');
        if (gradeCtx && !gradeDistributionChartInstance) {
            gradeDistributionChartInstance = new Chart(gradeCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($gradeDistribution->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($gradeDistribution->values()) !!},
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)', 'rgba(59, 130, 246, 0.8)',
                            'rgba(234, 179, 8, 0.8)', 'rgba(249, 115, 22, 0.8)',
                            'rgba(239, 68, 68, 0.8)', 'rgba(107, 114, 128, 0.8)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    };

    Alpine.effect(() => {
        if (Alpine.store('activeTab') === 'analytics') {
            // Delay to allow tab transition to complete
            setTimeout(() => {
                initializeCharts();
            }, 150);
        }
    });
});
</script>