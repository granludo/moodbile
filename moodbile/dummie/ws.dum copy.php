<?php
$request = json_decode(urldecode($_POST['request']), TRUE);
switch ($request['wsfunction']) {
    case "moodle_course_get_courses_by_userid":
        $courses = array(
            0 => array(
                "id" => "4",
                "sortorder" => "10001",
                "shortname" => "TTC",
                "idnumber" => "",
                "category" => "1",
                "fullname" => "Third Test Course",
                "guest" => 0,
                "startdate" => 1277935200,
                "visible" => 1,
                "newsitems" => "5",
                "cost" => "", 
                "enrol" => "",
                "groupmode" => 0,
                "groupmodeforce" => 0,
                "summary" => "<p>Welcome to the Third Test Course</p>",
                "format" => "topics",
                "timecreated" => 1271241268,
                "timemodified" => 1271241268,
                "categorypath" => "/1",
                "context" => (object) array(
                    "id" => "26",
                    "path" => "/1/3/26",
                    "depth" => "3",
                    "contextlevel" => "50",
                    "instanceid" => "4"
                ),
                "lastcoursesync" => 1271768598,
                "modules" => array(
                    0 => (object) array(
                        "id" => "4",
                        "course" => "4",
                        "module" => "7",
                        "instance" => "2",
                        "section" => "14",
                        "idnumber" => "",
                        "added" => 1271241306,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "News forum",
                        "modname" => "forum",
                        "intro" => "General news and announcements",
                        "introformat" => 0
                    ),
                    1 => (object) array(
                        "id" => "6",
                        "course" => "4",
                        "module" => "7",
                        "instance" => "3",
                        "section" => "16",
                        "idnumber" => "",
                        "added" => 1271241438,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "TTC Forum",
                        "modname" => "forum",
                        "intro" => "<p>Welcome to the TTC Forum</p>",
                        "introformat" => 1
                    ),
                    2 => (object) array(
                        "id" => "5",
                        "course" => "4",
                        "module" => "15",
                        "instance" => "2",
                        "section" => "15",
                        "idnumber" => "",
                        "added" => 1274203810000,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "Layers OWL",
                        "modname" => "resource",
                        "intro" => "<p>Layers Owl file</p>",
                        "introformat" => 1
                    ),
                    3 => (object) array(
                        "id" => "12",
                        "course" => "4",
                        "module" => "23",
                        "instance" => "1",
                        "section" => "14",
                        "idnumber" => "",
                        "added" => 1271757345,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "asasd",
                        "modname" => "wiki",
                        "intro" => "<p>asdadasd</p>",
                        "introformat" => 1
                    )
                )
            ),
            1 => Array(
                "id" => "3",
                "sortorder" => "10002",
                "shortname" => "STC",
                "idnumber" => "",
                "category" => "1",
                "fullname" => "Second Test Course",
                "guest" => 0,
                "startdate" => 1267743600,
                "visible" => 1,
                "newsitems" => "5",
                "cost" => "",
                "enrol" => "",
                "groupmode" => 0,
                "groupmodeforce" => 0,
                "summary" => "",
                "format" => "weeks",
                "timecreated" => 1267710548,
                "timemodified" => 1267710548,
                "categorypath" => "/1",
                "context" => (object) array(
                    "id" => "16",
                    "path" => "/1/3/16",
                    "depth" => "3",
                    "contextlevel" => "50",
                    "instanceid" => "3"
                ),
                "lastcoursesync" => 1271768598,
                "modules" => Array(
                    0 => (object) array(
                        "id" => "7",
                        "course" => "3",
                        "module" => "7",
                        "instance" => "4",
                        "section" => "2",
                        "idnumber" => "",
                        "added" => 1271242710,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "News forum",
                        "modname" => "forum",
                        "intro" => "General news and announcements",
                        "introformat" => 0
                    )
                )
            ),
            2 => Array(
                "id" => "2",
                "sortorder" => "10003",
                "shortname" => "FTC",
                "idnumber" => "",
                "category" => "1",
                "fullname" => "First Test Course",
                "guest" => 0,
                "startdate" => 1267743600,
                "visible" => 1,
                "newsitems" => "5",
                "cost" => "",
                "enrol" => "",
                "groupmode" => 0,
                "groupmodeforce" => 0,
                "summary" => "<p>This is the first course in this test installation.</p>",
                "format" => "weeks",
                "timecreated" => 1267710488,
                "timemodified" => 1269600931,
                "categorypath" => "/1",
                "context" => (object) array(
                    "id" => "11",
                    "path" => "/1/3/11",
                    "depth" => "3",
                    "contextlevel" => "50",
                    "instanceid" => "2"
                ),
                "lastcoursesync" => 1271768598,
                "modules" => Array(
                    0 => (object) array(
                        "id" => "1",
                        "course" => "2",
                        "module" => "7",
                        "instance" => "1",
                        "section" => "1",
                        "idnumber" => "",
                        "added" => 1269007573,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "News forum",
                        "modname" => "forum",
                        "intro" => "General news and announcements",
                        "introformat" => 0
                    ),
                    1 => (object) array(
                        "id" => "8",
                        "course" => "2",
                        "module" => "7",
                        "instance" => "5",
                        "section" => "6",
                        "idnumber" => "",
                        "added" => 1271242762,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "Forum for FTC",
                        "modname" => "forum",
                        "intro" => "<p>This is the FTC Forum</p>",
                        "introformat" => 1
                    ),
                    2 => (object) array(
                        "id" => "2",
                        "course" => "2",
                        "module" => "15",
                        "instance" => "1",
                        "section" => "4",
                        "idnumber" => "",
                        "added" => 1271239736,
                        "score" => 0,
                        "indent" => 0,
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "OFU_Rules",
                        "modname" => "resource",
                        "intro" => "<p>A pdf file</p>",
                        "introformat" => 1
                    ),
                    3 => (object) array(
                        "id" => "11",
                        "course" => "2",
                        "module" => "15",
                        "instance" => "3",
                        "section" => "1",
                        "idnumber" => "",
                        "added" => 1274203810000,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "Catalog.xml",
                        "modname" => "resource",
                        "intro" => "<p>catalog.xml file</p>",
                        "introformat" => 1
                    ),
                    4 => (object) array(
                        "id" => "3",
                        "course" => "2",
                        "module" => "3",
                        "instance" => "1",
                        "section" => "5",
                        "idnumber" => "",
                        "added" => 1271239873,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "A Choice example",
                        "modname" => "choice",
                        "intro" => "<p>This is a choice example</p>",
                        "introformat" => 1
                    ),
                    6 => (object) array(
                        "id" => "10",
                        "course" => "2",
                        "module" => "5",
                        "instance" => "1",
                        "section" => "6",
                        "idnumber" => "",
                        "added" => 1271252138,
                        "score" => "0",
                        "indent" => "0",
                        "visible" => 1,
                        "visibleold" => 1,
                        "groupmode" => 0,
                        "groupingid" => 0,
                        "groupmembersonly" => 0,
                        "completion" => 0,
                        "completiongradeitemnumber" => "",
                        "completionview" => 0,
                        "completionexpected" => 0,
                        "availablefrom" => 0,
                        "availableuntil" => 0,
                        "showavailability" => 0,
                        "name" => "Feedback",
                        "modname" => "feedback",
                        "intro" => "<p>Leave a feedback</p>",
                        "introformat" => 1
                    )
                )
            )
        );         
        $json = $_POST["callback"] . "([" . json_encode($courses) . "])"; //JSONP
        echo $json;
        
        break;
    
    case "grade":
        
        if($request['gradeid'] == 0) {
            $grade = array(
                'id' => '0',
                'title' => 'Test',
                'grade' => '7.5',
                'description' => 'Trabajo sobre Query-by-example',
                'type' => 'asigment'
            );
        }
        if($request['gradeid'] == 1) {
            $grade = array(
                'id' => '1',
                'title' => 'Ejercicio Sostenibilidad',
                'grade' => '7.5',
                'description' => 'Indice de Pobreza Humana <b>(IPH)</b>',
                'type' => 'quiz'
            );
        }
        
        $json = $_POST["callback"] . "([" . json_encode($grade) . "])"; //JSONP
        
        echo $json;
        break;
        
    case "posts":
        $posts = array(
                 0 => array(
                    "id" => 0,
                    "forumid" => 0,
                    "title" => "¿Que es esto?",
                    "userid" => 50,
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f2.jpg",
                    "msg" => "Esto es una aplicación web que usa los web services de moodle",
                    "replyes" => array(
                        3 => array( //la key sera la id post
                            "id" => 30,
                            "userid" => 3,
                            "name" => "Marc",
                            "lastname" => "Alier",
                            "avatar" => "http://hongki.at/images/twitter_avatar/seeder_lab.jpg",
                            "title" => "Re: ¿Que es esto?",
                            "msg" => "y en que esta basado?"
                        ),
                         4 => array( //la key sera la id post
                            "id" => 40,
                            "userid" => 4,
                            "name" => "Jordi",
                            "lastname" => "Piquillem",
                            "avatar" => "http://hongki.at/images/twitter_avatar/seeder_lab.jpg",
                            "title" => "Re: ¿Que es esto?",
                            "msg" => "PHP + JS(jQuery) + HTML5"
                        )
                    )
                 ),
                 1 => array(
                    "id" => 1,
                    "forumid" => 0,
                    "title" => "eBooks",
                    "userid" => 50,
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f2.jpg",
                    "msg" => "eBooks es el nuevo gadget navideño",
                    "replyes" => array(
                        0 => array(
                            "id" => 50,
                            "userid" => 3,
                            "name" => "Imanol",
                            "lastname" => "Urra Ruiz",
                            "avatar" => "http://a3.twimg.com/profile_images/65892457/avatar11197_2_bigger.gif",
                            "title" => "Re: ¿Que es esto?",
                            "msg" => "y en que esta basado?"
                        ),
                        1 => array(
                            "id" => 60,
                            "userid" => 4,
                            "name" => "Imanol",
                            "lastname" => "Urra Ruiz",
                            "avatar" => "http://a3.twimg.com/profile_images/65892457/avatar11197_2_bigger.gif",
                            "title" => "Re: ¿Que es esto?",
                            "msg" => "PHP + JS(jQuery) + HTML5"
                        )
                    )
                 ),
                 2 => array(
                    "id" => 2,
                    "forumid" => 0,
                    "title" => "Post generico 1",
                    "userid" => 50,
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f2.jpg",
                    "msg" => "Esto es una aplicacion web que usa los web services de moodle",
                    "replyes" => array(
                        3 => array( //la key sera la id post
                            "id" => 70,
                            "userid" => 3,
                            "name" => "Imanol",
                            "lastname" => "Urra Ruiz",
                            "avatar" => "http://a3.twimg.com/profile_images/65892457/avatar11197_2_bigger.gif",
                            "title" => "Re: ¿Que es esto?",
                            "msg" => "y en que esta basado?"
                        ),
                         4 => array( //la key sera la id post
                            "id" => 80,
                            "userid" => 4,
                            "name" => "Imanol",
                            "lastname" => "Urra Ruiz",
                            "avatar" => "http://a3.twimg.com/profile_images/65892457/avatar11197_2_bigger.gif",
                            "title" => "Re: ¿Que es esto?",
                            "msg" => "PHP + JS(jQuery) + HTML5"
                        )
                    )
                 )
        );
        
        $json = $_POST["callback"] . "([" . json_encode($posts) . "])"; //JSONP
        
        echo $json;
        break;

    case "users":
        $users = array(
                0 => array(
                    "courseid" => 0,
                    "id" => 50,
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f2.jpg"
                ),
                1 => array(
                    "courseid" => 0,
                    "id" => 10,
                    "name" => "Marc",
                    "lastname" => "Alier",
                    "avatar" => "http://a3.twimg.com/profile_images/669231243/ludo-potachovizado_bigger.jpg"
                ),
                2 => array(
                    "courseid" => 0,
                    "id" => 30,
                    "name" => "Aritz",
                    "lastname" => "Tusell Garcia",
                    "avatar" => "http://a3.twimg.com/profile_images/65892457/avatar11197_2_bigger.gif"
                ),
                3 => array(
                    "courseid" => 0,
                    "id" => 20,
                    "name" => "Josep",
                    "lastname" => "Guillen",
                    "avatar" => "http://a3.twimg.com/profile_images/669231243/ludo-potachovizado_bigger.jpg"
                )
        );
        
        $json = $_POST["callback"] . "([" . json_encode($users) . "])"; //JSONP
        
        echo $json;
        break;
    
    case "events":
        $upcomingevents = array(
                0 => array(
                    "id" => 0,
                    "courseid" => 0,
                    "title" => "Entrega trabajo",
                    "enddata" => "01/10/2009",
                    "lastmodification" => '1265902358806',
                    "type" => "asigment",
                    "description" => "Trabajo sobre Query-by-example",
                    "section" => 12
                ),
                1 => array(
                    "id" => 1,
                    "courseid" => 0,
                    "title" => "Entrega trabajo Wikipedia",
                    "enddata" => "02/10/2009",
                    "type" => "asigment",
                    "description" => "Mejorar entrada sobre CBIR",
                    "section" => 13
                ),
                2 => array(
                    "id" => 32,
                    "courseid" => 0,
                    "title" => "Cuestionario de MPEG-2",
                    "enddata" => "23/12/2009",
                    "type" => "quiz",
                    "description" => "Cuestionario sobre el estandar MPEG-2",
                    "section" => 12
                ),
                3 => array(
                    "id" => 11,
                    "courseid" => 1,
                    "title" => "Limpiar codigo de Moodbile",
                    "enddata" => "10/1/2009",
                    "type" => "asigment",
                    "description" => "Generalizar peticiones, reestructurar llamadas...",
                    "section" => 23
                ),
                4 => array(
                    "id" => 21,
                    "courseid" => 0,
                    "title" => "Crear nueva entrada sobre Query-by-example",
                    "enddata" => "01/10/2009",
                    "type" => "asigment",
                    "description" => "Trabajo sobre Query-by-example",
                    "section" => 12
                ),
                5 => array(
                    "id" => 22,
                    "courseid" => 0,
                    "title" => "Cuestionario sobre los Amos del mundo",
                    "enddata" => "02/10/2009",
                    "type" => "quiz",
                    "description" => "¿Quienes son los amos del mundo segun la presentacion en Desenvolupament. Sostenible?",
                    "section" => 13
                ),
                6 => array(
                    "id" => 3,
                    "courseid" => 1,
                    "title" => "Cuestionario sobre Factor 4",
                    "enddata" => "01/10/2009",
                    "type" => "quiz",
                    "description" => "¿Que es el Factor 4?<br/>¿Que hay que hacer para reducir el consumo de energia?",
                    "section" => 22
                ),
                7 => array(
                    "id" => 4,
                    "courseid" => 1,
                    "title" => "Entrega proyecto Algorismia",
                    "enddata" => "02/10/2009",
                    "type" => "asigment",
                    "description" => "Creacion de un filtro en C++",
                    "section" => 23
                ),
                8 => array(
                    "id" => 40,
                    "courseid" => 1,
                    "title" => "Entrega trabajo",
                    "enddata" => "01/10/2009",
                    "type" => "asigment",
                    "description" => "Trabajo sobre Query-by-example",
                    "section" => 22
                ),
                9 => array(
                    "id" => 39,
                    "courseid" => 2,
                    "title" => "Entrega trabajo 2",
                    "enddata" => "02/10/2009",
                    "type" => "asigment",
                    "description" => "Moodbile",
                    "section" => 23
                ),
                10 => array(
                    "id" => 23,
                    "courseid" => 2,
                    "title" => "Entrega trabajo",
                    "enddata" => "01/10/2009",
                    "type" => "asigment",
                    "description" => "Trabajo sobre Query-by-example",
                    "section" => 32
                ),
                11 => array(
                    "id" => 12,
                    "courseid" => 2,
                    "title" => "Entrega trabajo 2",
                    "enddata" => "02/10/2009",
                    "type" => "asigment",
                    "description" => "Moodbile",
                    "section" => 33
                )
        );
        
        $json = $_POST["callback"] . "([" . json_encode($upcomingevents) . "])"; //JSONP
        
        echo $json;
        break;
    
    case "event":
        if($request['eventid'] == 0) {
            $event = array(
                "id" => 0,
                "courseid" => 0,
                "title" => "Entrega trabajo",
                "enddata" => "01/10/2009",
                "type" => "asigment",
                "description" => "Trabajo sobre Query-by-example",
                "section" => 12
            );
            
            $json = $_POST["callback"] . "([" . json_encode($event) . "])"; //JSONP
            echo $json;
        } else {
            $json = $_POST["callback"] . "([null])"; //JSONP
            echo $json;
        }
        
        break;
    
    case "profile":
        if($request['userid'] == 50) {
            $user = array(
                "id" => 50,
                "name" => "Imanol",
                "lastname" => "Urra Ruiz",
                "city" => "Terrassa",
                "email" => "index02@gmail.com",
                "courses" => array(
                    0 => "Comunicacions Audiovisuals",
                    1 => "Llenguatge i Sistemes Informatics",
                    2 => "Moodbile"
                ),
                "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f2.jpg",
                "roles" => "Estudent, Teacher",
            );
        }
        if($request['userid'] == 10) {
            $user = array(
                "id" => 10,
                "name" => "Marc",
                "lastname" => "Alier",
                "city" => "Badalona",
                "email" => "index02@gmail.com",
                "courses" => array(
                    0 => "Comunicacions Audiovisuals",
                    1 => "Llenguatge i Sistemes Informatics",
                    2 => "Moodbile"
                ),
                "avatar" => "http://a3.twimg.com/profile_images/669231243/ludo-potachovizado_bigger.jpg",
                "roles" => "Teacher",
            );
        }
        
        $json = $_POST["callback"] . "([" . json_encode($user) . "])"; //JSONP
        
        echo $json;
        break;
        
    case "moodle_user_get_users_by_username":
        if(is_array($request)) {
            if(strtolower($request['wsusername']) == 'demo' && $request['wspassword'] == '123456'){
                $user = array(
                    'id' => 50,
                    'lastlogin' => '1265815958806',
                    'name' => 'Imanol',
                    'lastname' => 'Urra Ruiz',
                    'email0' => 'index02@gmail.com',
                    'avatar' => 'http://a3.twimg.com/profile_images/701725831/avatar_bigger.png'
                );
            } else {
                $user = array(
                    'msg' => 'Invalid user or pass'
                );
            }
        
        } else {
            $user = array(
                'msg' => 'Algo pasa en Moodbile :('
            );
        }
        
        $json = $_POST["callback"] . "([" . json_encode($user) . "])"; //JSONP
        echo $json;
        
        break;
}