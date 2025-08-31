<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courses.store') }}" class="grid gap-4 md:grid-cols-2">
                        @csrf

                        <input name="course_name" placeholder="Course Name" value="{{ old('course_name') }}" 
                               required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                        
                        <input name="course_code" placeholder="Course Code" value="{{ old('course_code') }}" 
                               required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">

                        <select name="grade" required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                            <option value="">Select Grade</option>
                            <option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option>
                        </select>

                        <select name="credit_hours" required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                            <option value="">Select Credit Hours</option>
                            <option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>
                        </select>

                        <select name="semester" required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>

                        <select name="academic_year" required class="p-3 rounded-lg border dark:border-gray-700 dark:bg-gray-900 w-full">
                            @php
                                $current = date('Y');
                                for($y = $current + 1; $y >= $current - 8; $y--) {
                                    $label = ($y-1) . '/' . $y;
                                    echo "<option value=\"{$label}\">{$label}</option>";
                                }
                            @endphp
                        </select>

                        <div class="md:col-span-2 flex space-x-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                                Add Course
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
