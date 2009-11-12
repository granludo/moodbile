<?
switch ($_GET['op']) {
    case 0:
    
        if(!isset($_GET['courseid'])){
            $courses = array(
                0 => array(
                            "id" => "0",
                            "title" => "Curso 0",
                            "format" => "topic"
                          ),
                1 => array(
                            "id" => "1",
                            "title" => "Curso 1",
                            "format" => "topic"
                          ),
                2 => array(
                            "id" => "2",
                            "title" => "Curso 2",
                            "format" => "topic"
                          )
                );
        
            $json = $_GET["jsoncallback"] . "(" . json_encode($courses) . ")"; //JSONP
        
            echo $json;
        } else {
            $courses = array(
                "format" => "topic",
                "sections" => array(
                    0 => array(
                        "label" => "<h3>Presentacion</h3>",
                    ),
                    1 => array(
                        "label" => "Tema 1",
                    ),
                    2 => array(
                        "label" => "Tema 2",
                    ),
                    3 => array(
                        "label" => "Tema 3",
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
                            "id" => "0",
                            "title" => "Recurso 0",
                            "type" => "pdf",
                            "section" => 1
                          ),
                1 => array(
                            "id" => "1",
                            "title" => "Recurso 1",
                            "type" => "doc",
                            "section" => 2
                          ),
                2 => array(
                            "id" => "2",
                            "title" => "Recurso 2",
                            "type" => "pps",
                            "section" => 3
                          )
                );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($resources) . ")"; //JSONP
        
        echo $json;
        break;
    
    case 2:
        $grades = array( //Esta estructura de datos es teniendo en cuenta que un profesor vera las notas de todos
                 0 => array(
                    "id" => 50, //id del usuario
                    "grades" => array(
                                0 => array(
                                    'title' => 'Test',
                                    'grade' => '7.5',
                                    'type' => 'asigment'
                                    ),
                                1 => array(
                                    'title' => 'Ejercicio Sostenibilidad',
                                    'grade' => '7.5',
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
                 0 => array( //la key sera la id del foro
                    "id" => 0,
                    "title" => "Foro generico",
                    "type" => "forum",
                    "section" => 0
                 ),
                 1 => array( //la key sera la id del foro
                    "id" => 1,
                    "title" => "Foro generico 2",
                    "type" => "forum",
                    "section" => 0
                 ),
                 2 => array( //la key sera la id del foro
                    "id" => 2,
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
                 0 => array( //la key sera la id del foro
                    "id" => 0,
                    "title" => "¿Que es esto?",
                    "author" => "Pepito"
                 ),
                 1 => array( //la key sera la id del foro
                    "id" => 1,
                    "title" => "Post generico",
                    "author" => "Pepito"
                 ),
                 2 => array( //la key sera la id del foro
                    "id" => 2,
                    "title" => "Post generico 1",
                    "author" => "Pepito"
                 )
        );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($posts) . ")"; //JSONP
        
        echo $json;
        break;
        
    case 5:
        $posts = array(
                 0 => array( //la key sera la id post
                    "id" => 0,
                    "title" => "¿Que es esto?",
                    "author" => "Pepito",
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
        break;

    case 6:
        $upcomingevents = array(
                0 => array(
                "id" => 0,
                "title" => "Entrega trabajo",
                "enddata" => "01/10/2009",
                "type" => "asigment",
                "section" => 2
                    ),
                1 => array(
                "id" => 1,
                "title" => "Entrega trabajo 2",
                "enddata" => "02/10/2009",
                "type" => "asigment",
                "section" => 3
                    )
                );
        
        $json = $_GET["jsoncallback"] . "(" . json_encode($upcomingevents) . ")"; //JSONP
        
        echo $json;
        break;
}