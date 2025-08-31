<?php

namespace App\Helpers;

class GPAHelper
{
    public static function calculateGPA($courses)
    {
        $gradePointsMap = [
            'A' => 5,
            'B' => 4,
            'C' => 3,
            'D' => 2,
            'E' => 1,
            'F' => 0,
        ];

        $totalPoints = 0;
        $totalCredits = 0;

        foreach ($courses as $course) {
            $gradeValue = $gradePointsMap[$course->grade] ?? 0;
            $points = $gradeValue * $course->credit_hours; // dynamic scaling
            $totalPoints += $points;
            $totalCredits += $course->credit_hours;
        }

        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.00;
    }
}
