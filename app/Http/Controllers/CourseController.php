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
        // Data for authenticated users
        if (Auth::check()) {
            $courses = Course::where('user_id', Auth::id())
                ->orderBy('academic_year', 'asc')
                ->orderBy('semester', 'asc')
                ->get();

            // Analytics & Projection Data
            $semesters = [];
            $gpaTrend = [];
            $gradeDistribution = collect(['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0]);
            $cumulativeGPA = 0;
            $totalCredits = 0;

            if ($courses->isNotEmpty()) {
                // GPA Trend and Semester Labels
                $coursesBySemester = $courses->groupBy(fn($course) => $course->academic_year . '_' . $course->semester);

                foreach ($coursesBySemester as $key => $semesterCourses) {
                    [$year, $sem] = explode('_', $key);
                    $semesters[] = str_replace('/', '-', $year) . " (Sem {$sem})";
                    $gpaTrend[] = GPAHelper::calculateGPA($semesterCourses);
                }

                // Grade Distribution
                $gradeDistribution = $gradeDistribution->merge($courses->groupBy('grade')->map->count());

                // For Projection Calculator
                $totalCredits = $courses->sum('credit_hours');
                $cumulativeGPA = GPAHelper::calculateGPA($courses);
            }

            // Group courses for display (most recent year first)
            $groupedCourses = $courses->groupBy('academic_year')->map(fn($year) => $year->groupBy('semester'))->reverse();

            // Current Year Details
            $currentYear = $courses->last()->academic_year ?? null;
            $currentYearCourses = $groupedCourses[$currentYear] ?? collect();
            $currentYearGPA = [
                'semester1' => GPAHelper::calculateGPA($currentYearCourses[1] ?? collect()),
                'semester2' => GPAHelper::calculateGPA($currentYearCourses[2] ?? collect()),
                'cumulative' => GPAHelper::calculateGPA($currentYearCourses->flatten(1))
            ];

        } else {
            // Empty state for unauthenticated users
            $groupedCourses = collect();
            $currentYearGPA = ['semester1' => 0.00, 'semester2' => 0.00, 'cumulative' => 0.00];
            $currentYear = null;
            $semesters = [];
            $gpaTrend = [];
            $gradeDistribution = collect(['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0]);
            $cumulativeGPA = 0;
            $totalCredits = 0;
        }

        return view('courses.index', compact(
            'groupedCourses', 'currentYearGPA', 'currentYear',
            'semesters', 'gpaTrend', 'gradeDistribution',
            'cumulativeGPA', 'totalCredits'
        ));
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
