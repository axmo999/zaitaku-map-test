<?php

namespace App\Http\Controllers;

use App\Facility;
use App\M_question_cd;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Input;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Facility::whereIn('facility_type_id', [9,17])->get();

        //$datas = DB::table('facilities')->all();

        // $perPage = 20;

        // $page = Input::get('page', 1);

        // if ($page > ($datas->count() / $perPage)) {
        //     $page = 1;
        // }

        // $pageOffset = ($page * $perPage) - $perPage;

        // $results = $datas->slice($pageOffset, $perPage);

        //$datas = DB::table('facilities')->paginate(15);

        return view("html.index", ['datas'=> $datas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('html.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $facility = new Facility;
        $facility->fill($request->all())->save();
        return redirect('facility/'.$facility->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function show(int $facility)
    {
        $m_questions = M_question_cd::all();
        $facility_attr = Facility::with('answers.M_answer_cd')->find($facility);
        //dd($facility_attr);
        //dd($facility::with('answers.M_answer_cd'));
        return view("html.show", ['facility'=> $facility_attr, 'm_questions'=> $m_questions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function edit(Facility $facility)
    {
        return view('html.edit', ['facility' => $facility]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Facility $facility)
    {
        $facility->fill($request->all())->save();
        return redirect('facility/'.$facility->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect('facility');
    }
}
