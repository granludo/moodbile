<?
switch ($_GET['op']) {
    case 0:
    
        if(!isset($_GET['courseid'])){
            $courses = array(
                0 => array(
                    "id" => 0,
                    "title" => "Comunicacions Audiovisuals",
                    "format" => "topic",
                    "summary" => "Trata temas sobre <u>Televison/TDT(DVB-T)</u>"
                ),
                1 => array(
                    "id" => 1,
                    "title" => "Llenguatge i Sistemes Informatics",
                    "format" => "topic",
                    "summary" => "Sumario sobre LSI"
                ),
                2 => array(
                    "id" => 2,
                    "title" => "Moodbile",
                    "format" => "topic",
                    "summary" => "Curso sobre Moodbile"
                )
            );
        
            $json = $_GET["jsoncallback"] . "(" . json_encode($courses) . ")"; //JSONP
        
            echo $json;
        } else {
            $courses = array(
                "format" => "topic",
                "sections" => array(
                    0 => array(
                        "summary" => "<h1><u>Presentacion</u></h1>",
                        "labels" => array()
                    ),
                    1 => array(
                        "summary" => "Tema 1",
                        "labels" => array()
                    ),
                    2 => array(
                        "summary" => "Tema 2",
                        "labels" => array()
                    ),
                    3 => array(
                        "summary" => "Tema 3",
                        "labels" => array()
                    )
                )
            );
        
            $json = $_GET["jsoncallback"] . "(" . json_encode($courses) . ")"; //JSONP
        
            echo $json;
        }
        
        break;
    
    case 1:
        $resources = array(
                0 => array(
                    "courseid" => 0,
                    "resource" => array(
                            "id" => "0",
                            "title" => "Percepcio Audiovisual",
                            "type" => "pdf",
                            "description" => "Presentació sobre les limitacions dels sistemes de percepció audiovisual humans.",
                            "section" => 1
                    )
                ),
                1 => array(
                    "courseid" => 0,
                    "resource" => array(
                            "id" => "1",
                            "title" => "Recurso 1",
                            "type" => "doc",
                            "section" => 2
                    )
                ),
                2 => array(
                    "courseid" => 0,
                    "resource" => array(
                            "id" => "2",
                            "title" => "Recurso 2",
                            "type" => "pps",
                            "section" => 3
                    )
                ),
                3 => array(
                    "courseid" => 1,
                    "resource" => array(
                            "id" => "0",
                            "title" => "Percepcio Audiovisual",
                            "type" => "pdf",
                            "description" => "Presentació sobre les limitacions dels sistemes de percepció audiovisual humans.",
                            "section" => 1
                    )
                ),
                4 => array(
                    "courseid" => 1,
                    "resource" => array(
                            "id" => "1",
                            "title" => "Recurso 1",
                            "type" => "doc",
                            "section" => 2
                    )
                ),
                5 => array(
                    "courseid" => 1,
                    "resource" => array(
                            "id" => "2",
                            "title" => "Recurso 2",
                            "type" => "pps",
                            "section" => 3
                    )
                ),
                6 => array(
                    "courseid" => 2,
                    "resource" => array(
                            "id" => "0",
                            "title" => "Percepcio Audiovisual",
                            "type" => "pdf",
                            "description" => "Presentació sobre les limitacions dels sistemes de percepció audiovisual humans.",
                            "section" => 1
                    )
                ),
                7 => array(
                    "courseid" => 2,
                    "resource" => array(
                            "id" => "1",
                            "title" => "Recurso 1",
                            "type" => "doc",
                            "section" => 2
                    )
                ),
                8 => array(
                    "courseid" => 2,
                    "resource" => array(
                            "id" => 2,
                            "title" => "Recurso 2",
                            "type" => "pps",
                            "section" => 3
                    )
                )
        );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($resources) . ")"; //JSONP
        
        echo $json;
        break;
    
    case 2:
        //Esta estructura de datos es teniendo en cuenta que un profesor vera las notas de todos
        //En el caso de que sea un estudiante, se le devolvera unicamente la parte del array correspondiente a su usuario.
        $grades = array(
                 0 => array(
                    "courseid" => 0,
                    "id" => 50, //id del usuario
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "grades" => array(
                        0 => array(
                            'id' => '0',
                            'title' => 'Test',
                            'grade' => '7.5',
                            'description' => 'Trabajo sobre Query-by-example',
                            'type' => 'asigment'
                        ),
                        1 => array(
                            'id' => '1',
                            'title' => 'Ejercicio Sostenibilidad',
                            'grade' => '7.5',
                            'description' => 'Indice de Pobreza Humana <b>(IPH)</b>',
                            'type' => 'quiz'
                        )
                    )
                ),
                1 => array(
                    "courseid" => 1,
                    "id" => 50, //id del usuario
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "grades" => array(
                        0 => array(
                            'id' => '11',
                            'title' => 'Test',
                            'grade' => '7.5',
                            'description' => 'Trabajo sobre Query-by-example',
                            'type' => 'asigment'
                        ),
                        1 => array(
                            'id' => '12',
                            'title' => 'Ejercicio Sostenibilidad',
                            'grade' => '7.5',
                            'description' => 'Indice de Pobreza Humana <b>(IPH)</b>',
                            'type' => 'quiz'
                        )
                    )
                ),
                2 => array(
                    "courseid" => 1,
                    "id" => 10, //id del usuario
                    "name" => "Marc",
                    "lastname" => "Alier",
                    "grades" => array(
                        0 => array(
                            'id' => '23',
                            'title' => 'Test',
                            'grade' => '7.5',
                            'description' => 'Trabajo sobre Query-by-example',
                            'type' => 'asigment'
                        ),
                        1 => array(
                            'id' => '24',
                            'title' => 'Ejercicio Sostenibilidad',
                            'grade' => '7.5',
                            'description' => 'Indice de Pobreza Humana <b>(IPH)</b>',
                            'type' => 'quiz'
                        )
                    )
                )
        );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($grades) . ")"; //JSONP
        
        echo $json;
        break;
    
    case 3:
        $forums = array(
                 0 => array(
                    "id" => 0,
                    "courseid" => 0,
                    "title" => "Foro generico",
                    "type" => "forum",
                    "section" => 0
                 ),
                 1 => array(
                    "id" => 1,
                    "courseid" => 0,
                    "title" => "Foro generico 2",
                    "type" => "forum",
                    "section" => 0
                 ),
                 2 => array(
                    "id" => 2,
                    "courseid" => 0,
                    "title" => "Foro generico 3",
                    "type" => "forum",
                    "section" => 2
                 )
        );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($forums) . ")"; //JSONP
        
        echo $json;
        break;
    
    case 4:
        $posts = array(
                 0 => array(
                    "id" => 0,
                    "forumid" => 0,
                    "title" => "¿Que es esto?",
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f1.jpg",
                    "msg" => "Esto es una aplicación web que usa los web services de moodle",
                    "replyes" => array(
                        3 => array( //la key sera la id post
                            "id" => 3,
                            "title" => "Re: ¿Que es esto?",
                            "author" => "Joan",
                            "msg" => "y en que esta basado?"
                        ),
                         4 => array( //la key sera la id post
                            "id" => 4,
                            "title" => "Re: ¿Que es esto?",
                            "author" => "Pepito",
                            "msg" => "PHP + JS(jQuery) + HTML5"
                        )
                    )
                 ),
                 1 => array(
                    "id" => 1,
                    "forumid" => 0,
                    "title" => "eBooks",
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f1.jpg",
                    "msg" => "eBooks es el nuevo gadget navideño",
                    "replyes" => array(
                        3 => array( //la key sera la id post
                            "id" => 3,
                            "title" => "Re: ¿Que es esto?",
                            "author" => "Joan",
                            "msg" => "y que son los eBooks?"
                        ),
                         4 => array( //la key sera la id post
                            "id" => 4,
                            "title" => "Re: Re: ¿Que es esto?",
                            "author" => "Pepito",
                            "msg" => "Libros electronicos con tinta electronica"
                        )
                    )
                 ),
                 2 => array(
                    "id" => 2,
                    "forumid" => 0,
                    "title" => "Post generico 1",
                    "name" => "Imanol",
                    "lastname" => "Urra Ruiz",
                    "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f1.jpg",
                    "msg" => "Esto es una aplicacion web que usa los web services de moodle",
                    "replyes" => array(
                        3 => array( //la key sera la id post
                            "id" => 3,
                            "title" => "Re: ¿Que es esto?",
                            "author" => "Joan",
                            "msg" => "y en que esta basado?"
                        ),
                         4 => array( //la key sera la id post
                            "id" => 4,
                            "title" => "Re: ¿Que es esto?",
                            "author" => "Pepito",
                            "msg" => "PHP + JS(jQuery) + HTML5"
                        )
                    )
                 )
        );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($posts) . ")"; //JSONP
        
        echo $json;
        break;
        
    /*case 5:
        $posts = array(
                 0 => array( //la key sera la id post
                    "id" => 0,
                    "title" => "¿Que es esto?",
                    "author" => "Pepito",
                    
                 ),
                 
                 1 => array( //la key sera la id del foro
                    "id" => 1,
                    "title" => "Post generico",
                    "author" => "Pepito",
                    "msg" => "Esto es una aplicacion web que usa los web services de moodle",
                    "replyes" => array(
                        5 => array( //la key sera la id post
                            "id" => 5,
                            "title" => "Re: Post generico",
                            "author" => "Joan",
                            "msg" => "y en que esta basado?"
                        ),
                        6 => array( //la key sera la id post
                            "id" => 6,
                            "title" => "Re: Post generico",
                            "author" => "Pepito",
                            "msg" => "PHP + JS(jQuery) + HTML5"
                        )
                    )
                 ),
                 2 => array( //la key sera la id del foro
                    "id" => 2,
                    "title" => "Post generico 1",
                    "author" => "Pepito",
                    "msg" => "Esto es una aplicacion web que usa los web services de moodle",
                    "replyes" => array(
                        7 => array( //la key sera la id post
                            "id" => 7,
                            "title" => "Re: ¿Que es esto?",
                            "author" => "Joan",
                            "msg" => "y en que esta basado?"
                        ),
                        8 => array( //la key sera la id post
                            "id" => 8,
                            "title" => "Re: ¿Que es esto?",
                            "author" => "Pepito",
                            "msg" => "PHP + JS(jQuery) + HTML5"
                       )
                    )
                 )
        );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($posts[$_GET['postid']]) . ")"; //JSONP
        
        echo $json;
        break;*/

    case 6:
        $upcomingevents = array(
                0 => array(
                    "id" => 0,
                    "courseid" => 0,
                    "title" => "Entrega trabajo",
                    "enddata" => "01/10/2009",
                    "type" => "asigment",
                    "description" => "Trabajo sobre Query-by-example",
                    "section" => 2
                ),
                1 => array(
                    "id" => 1,
                    "courseid" => 0,
                    "title" => "Entrega trabajo Wikipedia",
                    "enddata" => "02/10/2009",
                    "type" => "asigment",
                    "description" => "Mejorar entrada sobre CBIR",
                    "section" => 3
                ),
                2 => array(
                    "id" => 32,
                    "courseid" => 0,
                    "title" => "Cuestionario de MPEG-2",
                    "enddata" => "23/12/2009",
                    "type" => "quiz",
                    "description" => "Cuestionario sobre el estandar MPEG-2",
                    "section" => 2
                ),
                3 => array(
                    "id" => 11,
                    "courseid" => 1,
                    "title" => "Limpiar codigo de Moodbile",
                    "enddata" => "10/1/2009",
                    "type" => "asigment",
                    "description" => "Generalizar peticiones, reestructurar llamadas...",
                    "section" => 3
                ),
                4 => array(
                    "id" => 21,
                    "courseid" => 0,
                    "title" => "Crear nueva entrada sobre Query-by-example",
                    "enddata" => "01/10/2009",
                    "type" => "asigment",
                    "description" => "Trabajo sobre Query-by-example",
                    "section" => 2
                ),
                5 => array(
                    "id" => 22,
                    "courseid" => 0,
                    "title" => "Cuestionario sobre los Amos del mundo",
                    "enddata" => "02/10/2009",
                    "type" => "quiz",
                    "description" => "¿Quienes son los amos del mundo segun la presentacion en Desenvolupament. Sostenible?",
                    "section" => 3
                ),
                6 => array(
                    "id" => 3,
                    "courseid" => 1,
                    "title" => "Cuestionario sobre Factor 4",
                    "enddata" => "01/10/2009",
                    "type" => "quiz",
                    "description" => "¿Que es el Factor 4?<br/>¿Que hay que hacer para reducir el consumo de energia?",
                    "section" => 2
                ),
                7 => array(
                    "id" => 4,
                    "courseid" => 1,
                    "title" => "Entrega proyecto Algorismia",
                    "enddata" => "02/10/2009",
                    "type" => "asigment",
                    "description" => "Creacion de un filtro en C++",
                    "section" => 3
                ),
                8 => array(
                    "id" => 40,
                    "courseid" => 1,
                    "title" => "Entrega trabajo",
                    "enddata" => "01/10/2009",
                    "type" => "asigment",
                    "description" => "Trabajo sobre Query-by-example",
                    "section" => 2
                ),
                9 => array(
                    "id" => 39,
                    "courseid" => 2,
                    "title" => "Entrega trabajo 2",
                    "enddata" => "02/10/2009",
                    "type" => "asigment",
                    "description" => "Moodbile",
                    "section" => 3
                ),
                10 => array(
                    "id" => 23,
                    "courseid" => 2,
                    "title" => "Entrega trabajo",
                    "enddata" => "01/10/2009",
                    "type" => "asigment",
                    "description" => "Trabajo sobre Query-by-example",
                    "section" => 2
                ),
                11 => array(
                    "id" => 12,
                    "courseid" => 2,
                    "title" => "Entrega trabajo 2",
                    "enddata" => "02/10/2009",
                    "type" => "asigment",
                    "description" => "Moodbile",
                    "section" => 3
                )
        );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($upcomingevents) . ")"; //JSONP
        
        echo $json;
        break;
    
    case 7:
        $user = array(
            "id" => 20,
            "name" => "Imanol",
            "lastname" => "Urra Ruiz",
            "email" => "index02@gmail.com",
            "courses" => array(
                0 => "Comunicacions Audiovisuals",
                1 => "Llenguatge i Sistemes Informatics",
                2 => "Moodbile"
            ),
            "avatar" => "http://terrassatsc.upc.edu/user/pix.php/1809/f1.jpg",
            "roles" => "",
        );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($user) . ")"; //JSONP
        
        echo $json;
        break;
}