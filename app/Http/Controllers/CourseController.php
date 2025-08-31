<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Helpers\GPAHelper;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            // Get all courses for logged-in user ordered by year then semester
            $courses = Course::where('user_id', Auth::id())
                ->orderBy('academic_year', 'desc') // Most recent first
                ->orderBy('semester', 'asc')
                ->get();

            // If no courses yet
            if ($courses->isEmpty()) {
                $groupedCourses = collect();
                $currentYearGPA = [
                    'semester1' => 0.00,
                    'semester2' => 0.00,
                    'cumulative' => 0.00
                ];
                $currentYear = null;
            } else {
                // Group courses by academic year and semester
                $groupedCourses = $courses->groupBy('academic_year')->map(function ($yearCourses) {
                    return $yearCourses->groupBy('semester');
                });

                // Get current year (most recent)
                $currentYear = $courses->first()->academic_year;
                $currentYearCourses = $groupedCourses[$currentYear] ?? collect();

                // Calculate current year GPAs
                $semester1Courses = $currentYearCourses[1] ?? collect();
                $semester2Courses = $currentYearCourses[2] ?? collect();
                $allCurrentYearCourses = $currentYearCourses->flatten(1);

                $currentYearGPA = [
                    'semester1' => GPAHelper::calculateGPA($semester1Courses),
                    'semester2' => GPAHelper::calculateGPA($semester2Courses),
                    'cumulative' => GPAHelper::calculateGPA($allCurrentYearCourses)
                ];
            }
        } else {
            // For unauthenticated users, show empty state
            $courses = collect();
            $groupedCourses = collect();
            $currentYearGPA = [
                'semester1' => 0.00,
                'semester2' => 0.00,
                'cumulative' => 0.00
            ];
            $currentYear = null;
        }

        return view('courses.index', compact('groupedCourses', 'currentYearGPA', 'currentYear'));
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to add courses.');
        }
        return view('courses.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to add courses.');
        }

        $request->validate([
            'course_name'   => 'required|string|max:255',
            'course_code'   => 'required|string|max:50',
            'grade'         => 'required|in:A,B,C,D,E,F',
            'credit_hours'  => 'required|integer|min:2|max:5',
            'semester'      => 'required|integer|in:1,2',
            'academic_year' => 'required|string',
        ]);

        Course::create([
            'user_id'       => Auth::id(),
            'name'          => $request->course_name,
            'code'          => $request->course_code,
            'grade'         => $request->grade,
            'credit_hours'  => $request->credit_hours,
            'semester'      => $request->semester,
            'academic_year' => $request->academic_year,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course added successfully.');
    }

    public function show(Course $course)
    {
        abort_unless($course->user_id === Auth::id(), 403);
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        abort_unless($course->user_id === Auth::id(), 403);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        abort_unless($course->user_id === Auth::id(), 403);

        $request->validate([
            'course_name'   => 'required|string|max:255',
            'course_code'   => 'required|string|max:50',
            'grade'         => 'required|in:A,B,C,D,E,F',
            'credit_hours'  => 'required|integer|min:2|max:5',
            'semester'      => 'required|integer|in:1,2',
            'academic_year' => 'required|string',
        ]);

        $course->update([
            'name'          => $request->course_name,
            'code'          => $request->course_code,
            'grade'         => $request->grade,
            'credit_hours'  => $request->credit_hours,
            'semester'      => $request->semester,
            'academic_year' => $request->academic_year,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        abort_unless($course->user_id === Auth::id(), 403);
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
