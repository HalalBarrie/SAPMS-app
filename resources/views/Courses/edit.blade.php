<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('courses.update', $course) }}" class="grid gap-4 md:grid-cols-2">
                        @csrf
                        @method('PUT')

                        <input name="course_name" value="{{ old('course_name', $course->name) }}" 
                               placeholder="Course Name" required
                               class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                        
                        <input name="course_code" value="{{ old('course_code', $course->code) }}" 
                               placeholder="Course Code" required
                               class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">

                        <select name="grade" required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                            <option value="">Select Grade</option>
                            @foreach(['A','B','C','D','E','F'] as $g)
                                <option value="{{ $g }}" {{ $course->grade === $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>

                        <select name="credit_hours" required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                            <option value="">Select Credit Hours</option>
                            @foreach([2,3,4,5] as $c)
                                <option value="{{ $c }}" {{ $course->credit_hours == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>

                        <select name="semester" required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                            <option value="1" {{ $course->semester == 1 ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ $course->semester == 2 ? 'selected' : '' }}>Semester 2</option>
                        </select>

                        <select name="academic_year" required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                            @php
                                $current = date('Y');
                                for($y = $current + 1; $y >= $current - 8; $y--) {
                                    $label = ($y-1) . '/' . $y;
                                    $sel = $course->academic_year === $label ? 'selected' : '';
                                    echo "<option value=\"{$label}\" {$sel}>{$label}</option>";
                                }
                            @endphp
                        </select>

                        <div class="md:col-span-2 flex space-x-4">
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                Update Course
                            </button>
                            <a href="{{ route('courses.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
