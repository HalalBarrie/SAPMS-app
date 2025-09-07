<div>
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">⚙️ Settings</h2>

        <!-- Current Academic Year/Semester -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Academic Details</h3>
            <form action="{{ route('profile.updateAcademicDetails') }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-xl">
                    <div>
                        <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Academic Year</label>
                        <select id="academic_year" name="current_academic_year" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                            <option value="">Select Year</option>
                            @for ($year = date('Y'); $year >= date('Y') - 10; $year--)
                                <option value="{{ $year }}/{{ $year+1 }}" {{ (Auth::user()->current_academic_year ?? '') == ($year . '/' . ($year+1)) ? 'selected' : '' }}>
                                    {{ $year }}/{{ $year+1 }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Semester</label>
                        <select id="semester" name="current_semester" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                            <option value="">Select Semester</option>
                            <option value="1" {{ (Auth::user()->current_semester ?? '') == 1 ? 'selected' : '' }}>1st Semester</option>
                            <option value="2" {{ (Auth::user()->current_semester ?? '') == 2 ? 'selected' : '' }}>2nd Semester</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                        Save Academic Details
                    </button>
                </div>
            </form>
        </div>

        <!-- Profile Information -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Profile Management</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Update your account's profile information, change your password, or delete your account.
            </p>
            <a href="{{ route('profile.edit') }}" class="inline-block px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                Go to Profile Page
            </a>
        </div>
    </div>
</div>