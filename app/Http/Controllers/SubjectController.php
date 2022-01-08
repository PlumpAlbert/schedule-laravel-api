<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Subject;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubjectController extends Controller
{

    private static function attendTime(Subject $subject)
    {
        return [
            'id' => $subject->id,
            'audience' => $subject->audience,
            'time' => $subject->time,
            'weekday' => $subject->weekday,
            'weekType' => $subject->weektype,
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'group' => ['required', 'integer']
        ]);
        if ($request->has('group')) {
            $groupId = $request->get('group');
        }
        $subjects = Subject::with('teacher')
            ->whereRelation('visitedBy', 'group_id', '=', $groupId);
        $groupedSubjects = [];
        foreach ($subjects->lazy(200) as $subject) {
            $composedKey = $subject->type . '~' . $subject->teacher->id . '~' . $subject->title;
            if (array_key_exists($composedKey, $groupedSubjects)) {
                $groupedSubjects[$composedKey]['times'][] = SubjectController::attendTime($subject);
            } else {
                $groupedSubjects[$composedKey] = [
                    'type' => $subject->type,
                    'title' => $subject->title,
                    'teacher' => $subject->teacher,
                    'times' => [SubjectController::attendTime($subject)]
                ];
            }
        }
        return Response([
            'error' => false,
            'message' => '',
            'body' => array_values($groupedSubjects)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'audience' => 'required',
            'type' => 'required',
            'title' => 'required|string',
            'time' => 'required',
            'weekday' => 'required|integer',
            'weekType' => 'required|integer',
            'teacher' => ['required', 'integer', 'exists:' . User::class . ',id'],
            'group' => ['integer', 'nullable', 'exists:' . Group::class . ',id']
        ]);
        $data['teacher_id'] = $data['teacher'];
        $data['weektype'] = $data['weekType'];
        $subject = Subject::create($data);
        if ($request->has('group')) {
            Visit::create([
                'subject_id' => $subject->id,
                'group_id' => $request->group
            ]);
        }
        $subject->teacher = User::find($subject->teacher_id);
        return Response([
            'error' => false,
            'message' => '',
            'body' => $subject
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', 'exists:' . Subject::class]]);
        $subject = Subject::with('teacher')->findOrFail($request->id);
        $changed = false;
        if ($request->has('audience')) {
            $subject->audience = $request->audience;
            $changed = true;
        }
        if ($request->has('title')) {
            $subject->title = $request->title;
            $changed = true;
        }
        if ($request->has('time')) {
            $subject->time = $request->time;
            $changed = true;
        }
        if ($request->has('type')) {
            $subject->type = $request->type;
            $changed = true;
        }
        if ($request->has('weekday')) {
            $subject->weekday = $request->weekday;
            $changed = true;
        }
        if ($request->has('weekType')) {
            $subject->weektype = $request->weekType;
            $changed = true;
        }
        if ($request->has('teacher')) {
            if (!User::find($request->teacher)) {
                return Response([
                    'error' => true,
                    'message' => 'Teacher with id "' . $request->teacher . '" does not exist'
                ], Response::HTTP_BAD_REQUEST);
            }

            $subject->teacher_id = $request->teacher;
            $changed = true;
        }
        if ($changed) $subject->save();
        return Response([
            'error' => false,
            'message' => '',
            'body' => $subject
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', 'exists:' . Subject::class . ',id']]);
        $deletedRows = Subject::where('id', $request->id)->delete();
        return Response([
            'error' => false,
            'message' => '',
            'body' => ['success' => $deletedRows === 1]
        ]);
    }
}
