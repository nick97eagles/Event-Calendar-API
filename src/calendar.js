window.onload = function () {
    document.getElementById("GET_button").onclick = getEvents;
    document.getElementById("GETID_button").onclick = listEvent;
    document.getElementById("POST_button").onclick = postEvents;
    document.getElementById("PUT_button").onclick = putEvents;
    document.getElementById("DELETE_button").onclick = deleteEvents;
    document.getElementById("ride_button").onclick = getRide;
    document.getElementById("search_button").onclick = searchEvent
    document.getElementById("todayE_button").onclick = todayEvent;
    document.getElementById("clear_button").onclick = function () {
        document.getElementById("display").innerHTML = "";
        document.getElementById("display2").innerHTML = "";
        document.getElementById("display3").innerHTML = "";
    }
}

//todayE_button 
function todayEvent(){

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Typical action to be performed when the document is ready:
            var results = JSON.parse(req.responseText);
            var txt = "";
            console.log(results.calendars);
            for (var i = 0, l = results.calendars.length; i < l; i++) {
                var obj = results.calendars[i];
                txt += "<table border = '1' id='container' style='margin: 10px;'>";
                for (elements in obj) {
                    txt += "<tr><td>" + elements + ": " + obj[elements] + "</td></td>";
                }
                txt += "</table>";
                document.getElementById('display').innerHTML = txt;
            }
        }
    };
    req.open("GET", "http://18.208.163.54:8888/api/v2/calendar/today", true);
    req.send();


}
//search_button
function searchEvent(){
    // Display search event name input
    document.getElementById("display").innerHTML =
        "<div style=\'margin-top: 10px;\'>" +
        "Enter the name of the event you would like to find : <input type=\'text\' id=\'search_event_name\'><br><br>" +
        "<button id=\'searchEvent_button\'>Search</button>" +
        "</div>";
    document.getElementById("searchEvent_button").onclick = findEvent;

    function findEvent() {
        // Obtain input and send GET request
        const event_name = document.getElementById("search_event_name").value;
        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var results = JSON.parse(req.responseText);
                if(results.length == 0){
                    document.getElementById("display").innerText = "There are no events with this name, have a dog instead";
                    getDogImage();
                }
                else{
                    // Display and format response
                    var txt ="";
                    for (var i = 0, l = results.calendars.length; i < l; i++) {
                        var obj = results.calendars[i];
                        txt += "<table border = '1' id='container' style='margin: 10px;'>";
                        for (elements in obj) {
                            txt += "<tr><td>" + elements + ": " + obj[elements] + "</td></td>";
                        }
                        txt += "</table>";
                    }
                    document.getElementById("display").innerHTML = txt;
                }
            }
        };
        req.open("GET", "http://18.208.163.54:8888/api/v2/calendar/search/?event_name=" + event_name, true);
        req.send();
    }

    
}


// ride_button
function getRide() {

    // Display search ride input
    document.getElementById("display").innerHTML =
        "<div style=\'margin-top: 10px;\'>" +
        "Input ride origin: <input type=\'text\' id=\'ride_origin\'><br><br>" +
        "<button id=\'findRide_button\'>Search</button>" +
        "</div>";
    document.getElementById("findRide_button").onclick = findRide;

    function findRide() {
        // Obtain input and send GET request
        const ride_origin = document.getElementById("ride_origin").value;
        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var results = JSON.parse(req.responseText);
                if(results.length == 0){
                    document.getElementById("display").innerText = "\nThere are no rides in this area at this moment";
                    getDogImage();
                }
                else{
                    // Display and format response
                    var txt = "";
                    for(var i =0; i<results.length; i++){
                        txt += "<table border = '1' id='container' style='margin: 10px;'>";
                        for (elements in results[i]) {
                            txt += "<tr><td>" + elements + ": " + results[i][elements] + "</td></td>";
                        }
                        txt += "</table>";
                    }
                    document.getElementById("display").innerHTML = txt;
                }
            }
        };
        req.open("GET", "http://35.169.132.99:8000/api/rides/search?origin=" + ride_origin, true);
        req.send();
    }
}

// gets all calendar events in DB
function getEvents() {
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Typical action to be performed when the document is ready:
            var results = JSON.parse(req.responseText);
            var txt = "";
            for (var i = 0, l = results.data.length; i < l; i++) {
                var obj = results.data[i];
                txt += "<table border = '1' id='container' style='margin: 10px;'>";
                for (elements in obj) {
                    txt += "<tr><td>" + elements + ": " + obj[elements] + "</td></td>";
                }
                txt += "</table>";
                document.getElementById('display').innerHTML = txt;
            }
        }
    };
    req.open("GET", "http://18.208.163.54:8888/api/v2/calendar", true);
    req.send();
}

// lists event specified by id
function listEvent() {

    function findList() {
        const list_id = document.getElementById("list_id").value;
        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Typical action to be performed when the document is ready:
                var results = JSON.parse(req.responseText);
                var txt = "";
                var obj = results.calendar;
                txt += "<table border = '1' id='container' style='margin: 10px;'>";
                for (elements in obj) {
                    txt += "<tr><td>" + elements + ": " + obj[elements] + "</td></td>";
                }
                txt += "</table>";
                document.getElementById("display").innerHTML = txt;
                // show guest list that matches with event 
                document.getElementById("display2").innerHTML =
                    "<button id=\'gl_button\'>Show Guest List</button>";
                document.getElementById('gl_button').onclick = function () { displayGL(list_id); }
            }
        };
        req.open("GET", "http://18.208.163.54:8888/api/v2/calendar/" + list_id, true);
        req.send();
    }

    // get id of event to list
    document.getElementById("display").innerHTML =
        "<div style=\'margin-top: 10px;\'>" +
        "Input id of event to search: <input type=\'text\' id=\'list_id\'><br><br>" +
        "<button id=\'remove_event_button\'>Search</button>" +
        "</div>";

    document.getElementById("remove_event_button").onclick = findList;

}

// creates calendar event and submits to the DB
function postEvents() {

    // submits data to DB using POST request
    function submitData() {
        // create empty array
        var data = {};
        // gets user input and fills array
        data.event_name = document.getElementById("event_name").value;
        data.event_desc = document.getElementById("event_desc").value;
        data.location = document.getElementById("location").value;
        data.event_date = document.getElementById("event_date").value;
        data.hosted_by = document.getElementById('hosted_by').value;

        var json = JSON.stringify(data);
        var req = new XMLHttpRequest();

        req.open("POST", "http://18.208.163.54:8888/api/v2/calendar", true);
        req.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        // response message
        req.onload = function () {
            if (req.status == '200') {
                document.getElementById("display").innerText = "Data was successfully submitted";
            }
            else {
                document.getElementById("display").innerText = "Oops, something went wrong";
            }
        }
        req.send(json);
    }
    // input fields
    document.getElementById("display").innerHTML =
        "<div style=\'margin-top: 15px;\'>" +
        "Fill in input fields to create event" +
        "<form id=\'calendar_post\'>" +
        "event_name: <input type=\'text\' id=\'event_name\'><br>" +
        "event_desc: <input type=\'text\' id=\'event_desc\'><br>" +
        "location: <input type=\'text\' id=\'location\'><br>" +
        "event_date: <input type=\'text\' id=\'event_date\'><br>" +
        "hosted_by: <input type=\'text\' id=\'hosted_by\'><br>" +
        "</form><br>" +
        "<button id=\'post\'>Submit</button>" +
        "</div>";

    document.getElementById("post").onclick = submitData;
}

// updates calendar event 
function putEvents() {

    // submits data to DB using PUT request
    function changeData() {
        // gets id of event to update
        const put_id = document.getElementById("event_id").value;
        // creates empty array
        var data = {};
        // gets user input and fills array
        data.event_name = document.getElementById("event_name").value;
        data.event_desc = document.getElementById("event_desc").value;
        data.location = document.getElementById("location").value;
        data.event_date = document.getElementById("event_date").value;
        data.hosted_by = document.getElementById('hosted_by').value;

        var json = JSON.stringify(data);
        var req = new XMLHttpRequest();

        req.open("PUT", "http://18.208.163.54:8888/api/v2/calendar/" + put_id, true);
        req.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        // response message
        req.onload = function () {
            if (req.status == '200') {
                document.getElementById("display").innerText = "Data was successfully submitted";
            }
            else {
                document.getElementById("display").innerText = "Oops, something went wrong";
            }
        }
        req.send(json);
    }
    // input fields
    document.getElementById("display").innerHTML =
        "<div style=\'margin-top: 15px;\'>" +
        "Input id of event to update: <input type=\'text\' id=\'event_id\'><br><br>" +
        "Fill in input fields to update event<br>" +
        "event_name: <input type=\'text\' id=\'event_name\'><br>" +
        "event_desc: <input type=\'text\' id=\'event_desc\'><br>" +
        "location: <input type=\'text\' id=\'location\'><br>" +
        "event_date: <input type=\'text\' id=\'event_date\'><br>" +
        "hosted_by: <input type=\'text\' id=\'hosted_by\'><br>" +
        "<button id=\'put\'>Submit</button>" +
        "</div>";

    document.getElementById("put").onclick = changeData;
}

// deletes calendar event
function deleteEvents() {
    // deletes event from DB
    function deleteCall() {
        const remove_id = document.getElementById("remove_id").value;
        var req = new XMLHttpRequest();
        req.open("DELETE", "http://18.208.163.54:8888/api/v2/calendar/" + remove_id, true);
        req.send();
    }

    // get id of event to delete
    document.getElementById("display").innerHTML =
        "<div style=\'margin-top: 10px;\'>" +
        "Input id of event to remove: <input type=\'text\' id=\'remove_id\'><br><br>" +
        "<button id=\'remove_event_button\'>Remove</button>" +
        "</div>";

    document.getElementById("remove_event_button").onclick = deleteCall;
}

// shows guest list 
function displayGL(id) {
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var results = JSON.parse(req.responseText);
            var txt = "";
            for (var i = 0, l = results.data.length; i < l; i++) {
                var obj = results.data[i];
                if (obj.event_id == id) {
                    txt += "<table border = '1' id='container' style=\'margin: 10px;\'>";
                    for (elements in obj) {
                        txt += "<tr><td>" + elements + ": " + obj[elements] + "</td></td>";
                    }
                    txt += "</table>";
                    document.getElementById('display2').innerHTML = txt;
                }
                else if (txt === "") {
                    document.getElementById('display2').innerHTML =
                        "<p>There are no guest lists</p>" +
                        "<button id=\'POST_GL\'>Add Guest List</button>";
                    document.getElementById('POST_GL').onclick = postGL;
                }
            }
            document.getElementById('display3').innerHTML =
                "<button id=\'put_gl\'>Update Guest List</button>" +
                "<button id=\'delete_gl\'>Remove Guest List</button>";

            document.getElementById('put_gl').onclick = putGL;
            document.getElementById('delete_gl').onclick = deleteGL;
        }
    };
    req.open("GET", "http://18.208.163.54:8888/api/v2/guest_list", true);
    req.send();
}

// creates guest list
function postGL() {

    // submits data to DB using POST request
    function submitData() {
        // create empty array
        var data = {};
        // gets user input and fills array
        data.student_id = document.getElementById("student_id").value;
        data.event_id = document.getElementById("event_id").value;
        data.student_name = document.getElementById("student_name").value;
        data.phone_number = document.getElementById("phone_number").value;

        var json = JSON.stringify(data);
        var req = new XMLHttpRequest();

        req.open("POST", "http://18.208.163.54:8888/api/v2/guest_list", true);
        req.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        // response message
        req.onload = function () {
            if (req.status == '200') {
                document.getElementById("display").innerText = "Data was successfully submitted";
                document.getElementById("display2").innerText = "";
            }
            else {
                document.getElementById("display").innerText = "Oops, something went wrong";
            }
        }
        req.send(json);
    }
    // input fields 
    document.getElementById('display2').innerHTML =
        "<div style=\'margin-top: 15px;\'>" +
        "Make sure event_id matches event above<br>" +
        "guest_id: <input type=\'text\' id=\'student_id\'><br>" +
        "event_id: <input type=\'text\' id=\'event_id\'><br>" +
        "guest_name: <input type=\'text\' id=\'student_name\'><br>" +
        "phone_number: <input type=\'text\' id=\'phone_number\'><br>" +
        "<button id=\'gl_post\'>Submit</button>" +
        "</div>";

    document.getElementById('gl_post').onclick = submitData;
}

// udpates a guest list
function putGL() {
    // submits data to DB using PUT request
    function changeList() {
        // gets id of event to update
        const guest_list_id = document.getElementById("guest_id").value;
        // creates empty array
        var data = {};
        // gets user input and fills array
        data.event_id = document.getElementById("event_id").value;
        data.student_name = document.getElementById("student_name").value;
        data.phone_number = document.getElementById("phone_number").value;

        var json = JSON.stringify(data);
        var req = new XMLHttpRequest();

        req.open("PUT", "http://18.208.163.54:8888/api/v2/guest_list/" + guest_list_id, true);
        req.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        // response message
        req.onload = function () {
            if (req.status == '200') {
                document.getElementById("display3").innerText = "Data was successfully submitted";
            }
            else {
                document.getElementById("display3").innerText = "Oops, something went wrong";
            }
        }
        req.send(json);
    }
    // input fields 
    document.getElementById('display3').innerHTML =
        "<div style=\'margin-top: 15px;\'>" +
        "Input id of student you would like to update: <input type=\'text\' id=\'guest_id\'><br><br>" +
        "event_id: <input type=\'text\' id=\'event_id\'><br>" +
        "student_name: <input type=\'text\' id=\'student_name\'><br>" +
        "phone_number: <input type=\'text\' id=\'phone_number\'><br>" +
        "<button id=\'put_gl\'>Update</button>";

    document.getElementById('put_gl').onclick = changeList;
}

// removes member from guest list
function deleteGL() {
    // deletes event from DB
    function deleteMember() {
        const remove_id = document.getElementById("gl_remove_id").value;
        var req = new XMLHttpRequest();
        req.open("DELETE", "http://18.208.163.54:8888/api/v2/guest_list/" + remove_id, true);
        req.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        req.onload = function () {
            if (req.status == '200') {
                document.getElementById('display3').innerText = "Data was successfully removed";
            }
            else {
                document.getElementById('display3').innerText = "Oops, something went wrong";
            }
        }
        req.send();
    }

    // get id of event to delete
    document.getElementById("display3").innerHTML =
        "<div style=\'margin-top: 10px;\'>" +
        "Input id of student to remove: <input type=\'text\' id=\'gl_remove_id\'><br><br>" +
        "<button id=\'remove_gl_button\'>Remove</button>" +
        "</div>";

    document.getElementById("remove_gl_button").onclick = deleteMember;
}

// get a picture of a good boi
function getDogImage(){
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Typical action to be performed when the document is ready:
            var results = JSON.parse(req.responseText);
            goodboi = results.message;
            document.getElementById("display2").innerHTML = 
            "<br>Here is a picture of a good boi to cheer you up<br>" +
            "<img src=\'" + goodboi+ "\' alt=\'doggo\'>"
        }
    };
    req.open("GET", "https://dog.ceo/api/breeds/image/random", true);
    req.send();
}
