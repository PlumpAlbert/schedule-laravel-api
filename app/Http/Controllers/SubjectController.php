<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Subject;
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
        $request->validate([
            'audience' => 'required',
            'type' => 'required',
            'name' => 'required|string',
            'time' => 'required|integer',
            'weekday' => 'required|integer',
            'weekType' => 'required|integer',
            'teacher' => 'required|integer',
        ]);
        Subject::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
