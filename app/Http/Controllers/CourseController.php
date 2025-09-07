<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Helpers\GPAHelper;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // --- GUEST USERS ---
        if (Auth::guest()) {
            return view('courses.index', [
                'allCourses' => collect(),
                'groupedCourses' => collect(),
                'currentSemesterGPA' => 0.00,
                'cumulativeGPA' => 0.00,
                'gradeDistribution' => collect(),
                'goals' => collect(),
                'currentYear' => date('Y'),
                'currentSemester' => 1,
                'totalCredits' => 0,
                'semesterLabels' => [],
                'semesterGpaValues' => [],
            ]);
        }

        // --- AUTHENTICATED USERS ---
        $user = Auth::user();

        // 1. FETCH CORE DATA
        $allCourses = $user->courses()->orderBy('academic_year', 'asc')->orderBy('semester', 'asc')->get();
        $activeGoals = $user->goals()->where('status', 'active')->get();

        // 2. INITIALIZE ALL VARIABLES
        $currentSemesterGPA = 0.00;
        $cumulativeGPA = 0.00;
        $gradeDistribution = collect();
        $totalCredits = 0;
        $semesterLabels = [];
        $semesterGpaValues = [];
        $currentAcademicYear = $user->current_academic_year;
        $currentSemester = $user->current_semester;

        // 3. PERFORM CALCULATIONS (only if courses exist)
        if ($allCourses->isNotEmpty()) {
            $totalCredits = $allCourses->sum('credit_hours');
            $cumulativeGPA = GPAHelper::calculateGPA($allCourses);
            $gradeDistribution = $allCourses->groupBy('grade')->map->count();
            
            $coursesBySemester = $allCourses->groupBy(fn($c) => $c->academic_year . '-S' . $c->semester);

            $sortedSemesters = $coursesBySemester->sortKeys();
            foreach ($sortedSemesters as $key => $coursesInSemester) {
                $semesterLabels[] = $key;
                $semesterGpaValues[] = GPAHelper::calculateGPA($coursesInSemester);
            }

            if (!$currentAcademicYear || !$currentSemester) {
                $latestCourse = $allCourses->last();
                $currentAcademicYear = $latestCourse->academic_year;
                $currentSemester = $latestCourse->semester;
            }
            $currentSemesterKey = $currentAcademicYear . '-S' . $currentSemester;
            if(isset($coursesBySemester[$currentSemesterKey])) {
                $currentSemesterGPA = GPAHelper::calculateGPA($coursesBySemester[$currentSemesterKey]);
            }
        }

        // This is for the Courses tab display logic
        $groupedCourses = $allCourses->groupBy('academic_year')->map(fn($year) => $year->groupBy('semester'));

        // 4. PREPARE VIEW DATA
        $viewData = [
            'allCourses' => $allCourses,
            'groupedCourses' => $groupedCourses,
            'currentSemesterGPA' => $currentSemesterGPA,
            'cumulativeGPA' => $cumulativeGPA,
            'gradeDistribution' => $gradeDistribution,
            'goals' => $activeGoals,
            'currentYear' => $currentAcademicYear,
            'currentSemester' => $currentSemester,
            'totalCredits' => $totalCredits,
            'semesterLabels' => $semesterLabels,
            'semesterGpaValues' => $semesterGpaValues,
        ];

        // 5. RETURN VIEW
        return view('courses.index', $viewData);
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

    public function downloadPDF($academic_year, $semester)
    {
        $user = Auth::user();
        $coursesQuery = $user->courses()->where('academic_year', $academic_year);

        if ($semester !== 'all') {
            $coursesQuery->where('semester', $semester);
        }

        $courses = $coursesQuery->orderBy('semester', 'asc')->get();

        if ($courses->isEmpty()) {
            return back()->with('error', 'No courses found to generate PDF.');
        }

        // Calculate GPA for the selection
        $gpa = GPAHelper::calculateGPA($courses);

        $data = [
            'courses' => $courses,
            'academic_year' => $academic_year,
            'semester' => $semester,
            'user' => $user,
            'gpa' => $gpa,
            'date' => date('Y-m-d')
        ];

        $pdf = Pdf::loadView('pdf.courses', $data);

        $filename = 'courses-' . str_replace('/', '-', $academic_year) . '-' . $semester . '.pdf';
        return $pdf->download($filename);
    }

    public function downloadAllPDF()
    {
        $user = Auth::user();
        $courses = $user->courses()->orderBy('academic_year', 'asc')->orderBy('semester', 'asc')->get();

        if ($courses->isEmpty()) {
            return back()->with('error', 'No courses found to generate PDF.');
        }

        // Calculate GPA for all courses
        $gpa = GPAHelper::calculateGPA($courses);

        $data = [
            'courses' => $courses,
            'academic_year' => 'All Years',
            'semester' => 'all',
            'user' => $user,
            'gpa' => $gpa,
            'date' => date('Y-m-d')
        ];

        $pdf = Pdf::loadView('pdf.courses', $data);

        $filename = 'courses-all-time.pdf';
        return $pdf->download($filename);
    }
}
