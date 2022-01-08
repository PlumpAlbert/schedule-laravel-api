<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $group = null;
        if ($request->has('id')) {
            $group = Group::find($request->id);
        } else {
            $group = Group::all();
        }
        $response = null;
        if (!$group) {
            $response = new Response([
                'error' => true,
                'message' => 'Group not found',
                'body' => null
            ], 404);
        } else {
            $response = new Response([
                'error' => false,
                'message' => '',
                'body' => $group
            ]);
        }
        return $response;
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
            'faculty' => 'required|string',
            'specialty' => 'required|string',
            'year' => 'required|integer',
        ]);
        $group = Group::create($request->all(['faculty', 'specialty', 'year']));
        return Response([
            'error' => false,
            'message' => 'Group successfully created',
            'body' => $group
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    public function specialties(Request $request)
    {
        $request->validate([
            'faculty' => [ 'required', 'string' ]
        ]);
        $groups = Group::where('faculty', $request->faculty)->get();
        $body = [];
        $month = date("n");
        $year = $month > 9 ? date("Y") + 1 : date("Y");
        foreach($groups->lazy() as $group) {
            if (!isset($body[$group->specialty])) {
                $body[$group->specialty] = [];
            }
            $body[$group->specialty][$year - $group->year] = $group->id;
        }
        return Response([
            'error' => false,
            'message' => '',
            'body' => $body
        ]); 
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
