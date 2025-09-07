<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:semester,cumulative',
            'target_gpa' => 'required|numeric|min:0|max:5',
        ]);

        $user = Auth::user();
        $academicYear = $user->current_academic_year;
        $semester = $user->current_semester;

        // Prevent duplicate active goals
        Goal::where('user_id', $user->id)
            ->where('type', $request->type)
            ->where('status', 'active')
            ->update(['status' => 'completed']); // Deactivate old goal

        Goal::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'target_gpa' => $request->target_gpa,
            'academic_year' => $request->type === 'semester' ? $academicYear : null,
            'semester' => $request->type === 'semester' ? $semester : null,
            'status' => 'active',
        ]);

        return redirect()->route('courses.index', ['tab' => 'goals'])->with('success', 'Goal set successfully!');
    }

    public function destroy(Goal $goal)
    {
        // Ensure the user owns the goal
        abort_unless($goal->user_id === Auth::id(), 403);

        $goal->delete();

        return redirect()->route('courses.index', ['tab' => 'goals'])->with('success', 'Goal removed successfully!');
    }
}