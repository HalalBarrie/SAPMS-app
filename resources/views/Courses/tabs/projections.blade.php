<div x-show="$store.app.activeTab === 'projections'" x-transition>
    @auth
        <div x-data="gpaProjectionCalculator({{ is_numeric($cumulativeGPA) ? $cumulativeGPA : 0 }}, {{ $totalCredits ?? 0 }})" class="bento-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            
            <!-- Current Stats -->
            <div class="bento-card" style="grid-column: span 1;">
                <h3 class="text-lg font-bold text-white mb-4">Current Standing</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-900/50 rounded-lg">
                        <p class="text-sm text-indigo-300">Cumulative GPA</p>
                        <p class="text-3xl font-bold text-white" x-text="cumulativeGPA.toFixed(2)"></p>
                    </div>
                    <div class="p-4 bg-gray-900/50 rounded-lg">
                        <p class="text-sm text-indigo-300">Total Credits</p>
                        <p class="text-3xl font-bold text-white" x-text="totalCredits"></p>
                    </div>
                </div>
            </div>

            <!-- Projection Form -->
            <div class="bento-card" style="grid-column: span 2;">
                <h3 class="text-lg font-bold text-white mb-4">Add Projected Courses</h3>
                <div class="space-y-3 mb-4">
                    <template x-for="(course, index) in projectedCourses" :key="index">
                        <div class="flex items-center space-x-3 bg-gray-900/50 p-3 rounded-lg">
                            <select x-model.number="course.credit_hours" class="w-1/2 p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                                <option value="2">2 Credits</option>
                                <option value="3">3 Credits</option>
                                <option value="4">4 Credits</option>
                                <option value="5">5 Credits</option>
                            </select>
                            <select x-model.number="course.grade" class="w-1/2 p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                                <option value="5">A (5.0)</option>
                                <option value="4">B (4.0)</option>
                                <option value="3">C (3.0)</option>
                                <option value="2">D (2.0)</option>
                                <option value="1">E (1.0)</option>
                                <option value="0">F (0.0)</option>
                            </select>
                            <button @click="removeCourse(index)" x-show="projectedCourses.length > 1" class="p-2 bg-red-500/50 hover:bg-red-600 text-white rounded-full transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>
                <button @click="addCourse()" class="w-full px-4 py-2 bg-indigo-600/50 hover:bg-indigo-600 text-white rounded-lg transition-colors">
                    + Add Course
                </button>
            </div>

            <!-- Projected Result -->
            <div class="bento-card bg-green-600/20 border-green-500/50" style="grid-column: span 3;">
                 <h3 class="text-lg font-bold text-white mb-2">Projected Cumulative GPA</h3>
                 <p class="text-6xl font-bold text-center text-white" x-text="projectedGPA"></p>
                 <p class="text-center text-green-200" x-show="projectedGPA > cumulativeGPA.toFixed(2)">Your GPA will increase! 🎉</p>
                 <p class="text-center text-red-200" x-show="projectedGPA < cumulativeGPA.toFixed(2)">Your GPA will decrease.</p>
                 <p class="text-center text-gray-200" x-show="projectedGPA == cumulativeGPA.toFixed(2)">Your GPA will not change.</p>
            </div>
        </div>

        <script>
            function gpaProjectionCalculator(cumulativeGPA, totalCredits) {
                return {
                    cumulativeGPA: parseFloat(cumulativeGPA) || 0,
                    totalCredits: parseInt(totalCredits) || 0,
                    projectedCourses: [{ credit_hours: 3, grade: 5 }],
                    addCourse() { this.projectedCourses.push({ credit_hours: 3, grade: 5 }); },
                    removeCourse(index) { this.projectedCourses.splice(index, 1); },
                    get projectedGPA() {
                        let projectedPoints = 0;
                        let projectedCredits = 0;
                        this.projectedCourses.forEach(course => {
                            const grade = parseFloat(course.grade);
                            const credits = parseFloat(course.credit_hours);
                            if (!isNaN(grade) && !isNaN(credits) && credits > 0) {
                                projectedPoints += grade * credits;
                                projectedCredits += credits;
                            }
                        });
                        const currentTotalPoints = this.cumulativeGPA * this.totalCredits;
                        const newTotalPoints = currentTotalPoints + projectedPoints;
                        const newTotalCredits = this.totalCredits + projectedCredits;
                        if (newTotalCredits === 0) {
                            return this.cumulativeGPA > 0 ? this.cumulativeGPA.toFixed(2) : '0.00';
                        }
                        return (newTotalPoints / newTotalCredits).toFixed(2);
                    }
                }
            }
        </script>
        <style>
            .bento-select option {
                background: #1f2937 !important;
                color: white !important;
            }
        </style>
    @else
        <div class="text-center py-12 bg-gray-800 shadow-lg rounded-xl border border-gray-700">
            <div class="text-6xl mb-4">🧮</div>
            <h3 class="text-xl font-semibold mb-2 text-white">GPA Projections</h3>
            <p class="text-gray-400">Log in to calculate your future GPA.</p>
            <a href="{{ route('login') }}" class="mt-6 inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                Login
            </a>
        </div>
    @endauth
</div>
