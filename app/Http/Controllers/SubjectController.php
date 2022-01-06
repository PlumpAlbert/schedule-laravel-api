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
        $subject = Subject::with([Group::class, Visit::class])->get()->where('group_id', $groupId);
        return $subject;
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
            'name' => 'required|string',
            'time' => 'required',
            'weekday' => 'required|integer',
            'weekType' => 'required|integer',
            'teacher' => ['required', 'integer', 'exists:' . User::class . ',id']
        ]);
        $data['teacher_id'] = $data['teacher'];
        $data['weektype'] = $data['weekType'];
        unset($data['teacher']);
        unset($data['weekType']);
        $subject = Subject::create($data);
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
        if ($request->has('name')) {
            $subject->name = $request->name;
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
                ]);
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
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
