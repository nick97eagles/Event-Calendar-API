<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GuestListTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test the list endpoint for guest_list
     *
     * @return void
     */
    public function testList()
    {
        $guestList = factory('App\GuestList')->create();

        $this->get('api/v2/guest_list');

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'student_id',
                    'event_id',
                    'student_name',
                    'phone_number',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * Test the create endpoint for guest_list.
     * 
     * @return void
     */
    public function testCreate()
    {
        $body = [
            'event_id' => '29',
            'student_name' => 'Ramiro Harber',
            'phone_number' => '+1-274-229-1308',
        ];
        $this->post('/api/v2/guest_list', $body);
        $newGuestList = App\GuestList::where('student_id', 1)->first();
        $this->assertEquals($body['event_id'], $newGuestList->event_id);
        $this->assertEquals($body['student_name'], $newGuestList->student_name);
        $this->assertEquals($body['phone_number'], $newGuestList->phone_number);
        
        $this->seeStatusCode(200);
        $this->seeJsonEquals([
            'data' => [
                'event_id' => $body['event_id'],
                'student_name' => $body['student_name'],
                'phone_number' => $body['phone_number']
            ]
        ]);
    }

    /**
    * Test the read endpoint for guest_list
    * 
    *@return void
    */
    public function testRead()
    {
        //Create new guest_list
        $guestList = factory('App\GuestList')->create();
        //Send GET request
        $this ->get('api/v2/guest_list/' . $guestList->student_id);
        //test response
        $this->seeStatusCode(200);
        $this->seeJsonEquals([
            'guest_list' => [
                'student_id' => $guestList->student_id,
                'event_id' => $guestList->event_id,
                'student_name' => $guestList->student_name,
                'phone_number' => $guestList->phone_number,
                'updated_at' => $guestList->updated_at->format('Y-m-d H:i:s'),
                'created_at' => $guestList->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * 
     * @return void
     */
    public function testUpdate(){
        //create new guest_list
        $guestList = factory('App\GuestList')->create();
        //Send PUT request
        $body = [
            'event_id' => 29,
            'student_name' => 'Ramiro Harber',
            'phone_number' => '+1-274-229-1308',
        ];
        $this->put('/api/v2/guest_list/' . $guestList->student_id, $body);
        //test database
        $newGuestList = App\GuestList::where('student_id', 1)->first();
        $this->assertEquals($body['event_id'], $newGuestList->event_id);
        $this->assertEquals($body['student_name'], $newGuestList->student_name);
        $this->assertEquals($body['phone_number'], $newGuestList->phone_number);
        
        $this->seeStatusCode(200);
        $this->seeJsonEquals([
            'guest_list' => [
                'student_id' => 1,
                'event_id' => $body['event_id'],
                'student_name' => $body['student_name'],
                'phone_number' => $body['phone_number'],
                'updated_at' => $newGuestList->updated_at->format('Y-m-d H:i:s'),
                'created_at' => $newGuestList->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * 
     * @return void
     */
    public function testDestroy()
    {
        //create new guest_list
        $guestList = factory('App\GuestList')->create();
        //send DELETE request
        $this->delete('api/v2/guest_list/' . $guestList->student_id);
        //test database
        $this->assertFalse(App\GuestList::where('student_id', 1)->exists());
        //test response
        $this->seeStatusCode(200);
    } 

}
