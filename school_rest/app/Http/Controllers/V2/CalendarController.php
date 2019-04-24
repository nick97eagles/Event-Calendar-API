<?php

namespace App\Http\Controllers\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CalendarController extends Controller
{

    public function __construct(\App\Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->calendar->paginate(500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->calendar->create($input);

        return [
            'data' => $input
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->calendar->findorfail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $this->calendar->where('event_id', $id)->update($input);
        return $this->calendar->find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $calendar = $this->calendar->destroy($id);

        return [
            'message' => 'deleted successfully',
            'id' => $calendar
        ];
    }

    /**
     * Search for an event by its name
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $event_name = $request->input('event_name');
        return $this->calendar->where('event_name', 'like', "%$event_name%")->get();
    }

    /**
     * Search for an event by its name
     *
     * @return \Illuminate\Http\Response
     */
    public function today()
    {
        $current_date = date("Y-m-d");
        return $this->calendar->where('event_date', 'like', "%$current_date%")->get();
    }
}
