<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Trash;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trash  $trash
     * @return \Illuminate\Http\Response
     */
    public function show(Trash $trash)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trash  $trash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trash $trash)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trash  $trash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trash $trash)
    {
        //
    }
}
