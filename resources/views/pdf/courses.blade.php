<!DOCTYPE html>
<html>
<head>
    <title>Course Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 2px 0; }
        .summary { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
        .summary p { margin: 5px 0; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #777; }
        .no-break { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Academic Report</h1>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Report Period:</strong> 
            @if($semester !== 'all')
                {{ $academic_year }}, {{ $semester == 1 ? 'First' : 'Second' }} Semester
            @else
                {{ $academic_year }}
            @endif
        </p>
    </div>

    @php
        $grouped = $courses->groupBy('academic_year');
    @endphp

    @foreach($grouped as $year => $yearCourses)
        <div class="no-break">
            <h2>Academic Year: {{ $year }}</h2>
            @foreach($yearCourses->groupBy('semester') as $sem => $semesterCourses)
                <div class="no-break">
                    <h4>{{ $sem == 1 ? 'First' : 'Second' }} Semester</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Credit Hours</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($semesterCourses as $course)
                            <tr>
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->credit_hours }}</td>
                                <td>{{ $course->grade }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="summary">
                        <p>
                            <strong>Semester GPA:</strong> {{ number_format(App\Helpers\GPAHelper::calculateGPA($semesterCourses), 2) }} |
                            <strong>Total Credits:</strong> {{ $semesterCourses->sum('credit_hours') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="summary">
        <h3>Overall Report Summary</h3>
        <p><strong>Total Courses:</strong> {{ $courses->count() }}</p>
        <p><strong>Total Credit Hours:</strong> {{ $courses->sum('credit_hours') }}</p>
        <p><strong>Cumulative GPA for this period:</strong> {{ number_format($gpa, 2) }}</p>
    </div>

    <div class="footer">
        <p>Generated on: {{ $date }}</p>
    </div>
</body>
</html>
