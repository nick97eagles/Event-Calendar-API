<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CalendarTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test the list endpoint for calendars
     *
     * @return void
     */
    public function testList()
    {
        $calendar = factory('App\Calendar')->create();

        $this->get('api/v2/calendar');

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'event_id',
                    'event_name',
                    'event_desc',
                    'hosted_by',
                    'location',
                    'event_date',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * Test the create endpoint for calendars.
     * 
     * @return void
     */
    public function testCreate()
    {
        $body = [
            'event_name' => 'Scheduled Crying',
            'event_desc' => 'Cope for our grades',
            'hosted_by' => 'Leroy Jenkins',
            'location' => '3041 Krista Course Suite 953\nLoycemouth, MT 03154',
            'event_date' => '2018-10-19'
        ];
        $this->post('/api/v2/calendar', $body);
        $newCalendar = App\Calendar::where('event_id', 1)->first();
        $this->assertEquals($body['event_name'], $newCalendar->event_name);
        $this->assertEquals($body['event_desc'], $newCalendar->event_desc);
        $this->assertEquals($body['hosted_by'], $newCalendar->hosted_by);
        $this->assertEquals($body['location'], $newCalendar->location);
        $this->assertEquals($body['event_date'], $newCalendar->event_date);
        
        $this->seeStatusCode(200);
        $this->seeJsonEquals([
            'data' => [
                'event_name' => $body['event_name'],
                'event_desc' => $body['event_desc'],
                'hosted_by' => $body['hosted_by'],
                'location' => $body['location'],
                'event_date' => $body['event_date']
            ]
        ]);
    }

    /**
    * Test the read endpoint for calendars
    * 
    *@return void
    */
    public function testRead()
    {
        //Create new calendar
        $calendar = factory('App\Calendar')->create();
        //Send GET request
        $this ->get('api/v2/calendar/' . $calendar->event_id);
        //test response
        $this->seeStatusCode(200);
        $this->seeJsonEquals([
            'calendar' => [
                'event_id' => $calendar->event_id,
                'event_name' => $calendar->event_name,
                'event_desc' => $calendar->event_desc,
                'hosted_by' => $calendar->hosted_by,
                'location' => $calendar->location,
                'event_date' => $calendar->event_date->format('Y-m-d'),
                'updated_at' => $calendar->updated_at->format('Y-m-d H:i:s'),
                'created_at' => $calendar->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * 
     * @return void
     */
    public function testUpdate(){
        //create new calendar
        $calendar = factory('App\Calendar')->create();
        //Send PUT request
        $body = [
            'event_name' => 'Scheduled Crying',
            'event_desc' => 'Cope for our grades',
            'hosted_by' => 'Leroy Jenkins',
            'location' => '3041 Krista Course Suite 953\nLoycemouth, MT 03154',
            'event_date' => '2018-10-19'
        ];
        $this->put('/api/v2/calendar/' . $calendar->event_id, $body);
        //test database
        $newCalendar = App\Calendar::where('event_id', 1)->first();
        $this->assertEquals($body['event_name'], $newCalendar->event_name);
        $this->assertEquals($body['event_desc'], $newCalendar->event_desc);
        $this->assertEquals($body['hosted_by'], $newCalendar->hosted_by);
        $this->assertEquals($body['location'], $newCalendar->location);
        $this->assertEquals($body['event_date'], $newCalendar->event_date);
        
        $this->seeStatusCode(200);
        $this->seeJsonEquals([
            'calendar' => [
                'event_id' => 1,
                'event_name' => $body['event_name'],
                'event_desc' => $body['event_desc'],
                'hosted_by' => $body['hosted_by'],
                'location' => $body['location'],
                'event_date' => $body['event_date'],
                'updated_at' => $newCalendar->updated_at->format('Y-m-d H:i:s'),
                'created_at' => $newCalendar->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * 
     * @return void
     */
    public function testDestroy()
    {
        //create new calendar
        $calendar = factory('App\Calendar')->create();
        //send DELETE request
        $this->delete('api/v2/calendar/' . $calendar->event_id);
        //test database
        $this->assertFalse(App\Calendar::where('event_id', 1)->exists());
        //test response
        $this->seeStatusCode(200);
    } 

}
