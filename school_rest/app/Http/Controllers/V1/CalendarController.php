<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return json_decode('{
            "data":[
                {"event_id":132,"event_name":"CommUnity","event_desc":"Not Fun","created_at":"2012-03-31 06:13:50","event_date":"2019-01-26 07:43:22","hosted_by":"Walla Walla University"},
                {"event_id":133,"event_name":"Cptr320 project","event_desc":"Meet in CS Lab","created_at":"2011-11-06 11:59:59","event_date":"2019-02-24 15:26:11","hosted_by":"Cptr320"},
                {"event_id":134,"event_name":"Taqueria Burritos","event_desc":"Taqueria Burritos for Club Members","created_at":"2015-09-20 09:19:22","event_date":"2018-03-09 12:25:69","hosted_by":"Business Club"}
            ],
            "total":3
        }', true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return json_decode('{
            "data": {
                "event_name": "Scheduled Crying",
                "event_desc": "Cope for our grades",
                "created_at": "2015-09-7 07:24:43",
                "event_date": "2016-07-19 07:00:00",
                "hosted_by": "ASWWU"
            }
        }', true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return json_decode('{
            "event": {
                "event_id": 132,
                "event_name": "CommUnity",
                "event_desc": "Not Fun",
                "created_at": "2012-03-31 06:13:50",
                "event_date": "2019-01-26 07:43:22",
                "hosted_by": "Walla Walla University",
                "updated_at": "2018-04-13 13:53:52"
            }
        }', true);
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
        return json_decode('{
            "event": {
                "event_id": 132,
                "event_name": "CommUnity",
                "event_desc": "Not Fun",
                "created_at": "2012-03-31 06:13:50",
                "event_date": "2019-01-26 07:43:22",
                "hosted_by": "Walla Walla University",
                "updated_at": "2018-04-13 13:53:52"
            }
        }', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return [
            'message' => 'deleted successfully',
            'id' => 132
        ];
    }

}
