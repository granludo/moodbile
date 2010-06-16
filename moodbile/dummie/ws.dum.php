<?php
$request = json_decode(urldecode($_POST['request']), TRUE);
switch ($request['wsfunction']) {
    case "moodle_course_get_courses_by_userid":
        $json = $_POST["callback"] . '([{"id":"4","shortname":"Coms AD","category":"1","fullname":"Comunicacions Analogicas y Digitals","startdate":"1275696000","summary":"","format":"topics","timecreated":"1275659335","timemodified":"1275659335","lastcoursesync":1276036754,"modules":[{"id":"13","course":"4","module":"7","instance":"4","section":"23","idnumber":null,"added":"1276008805","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"News forum","modname":"forum","intro":"General news and announcements","introformat":"0","discussions":null}]},{"id":"3","shortname":"STC","category":"1","fullname":"Second Test Course","startdate":"1274313600","summary":"<p>Second Test Course Summary<\/p>","format":"topics","timecreated":"1274265890","timemodified":"1275472339","lastcoursesync":1276036754,"modules":[{"id":"3","course":"3","module":"7","instance":"2","section":"12","idnumber":null,"added":"1274458394","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"News forum","modname":"forum","intro":"General news and announcements","introformat":"0","discussions":{"2":{"id":"2","course":"3","forum":"2","name":"Second Discussion test","firstpost":"5","userid":"2","groupid":"-1","assessed":"1","timemodified":"1274733645","usermodified":"2","timestart":"0","timeend":"0","firstname":"Imanol","lastname":"Urra Ruiz","posts":{"5":{"id":"5","discussion":"2","parent":"0","userid":"2","created":"1274733645","modified":"1274733645","mailed":"0","subject":"Second Discussion test","message":"<p>JIJIJIJIJIJi<\/p>","messageformat":"1","messagetrust":"0","attachment":"","totalscore":"0","mailnow":"0","firstname":"Imanol","lastname":"Urra Ruiz"}}},"3":{"id":"3","course":"3","forum":"2","name":"Second Tree Discussion","firstpost":"6","userid":"2","groupid":"-1","assessed":"1","timemodified":"1274735834","usermodified":"2","timestart":"0","timeend":"0","firstname":"Imanol","lastname":"Urra Ruiz","posts":{"6":{"id":"6","discussion":"3","parent":"0","userid":"2","created":"1274735834","modified":"1274735834","mailed":"0","subject":"Second Tree Discussion","message":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur  sagittis ante quis sem feugiat quis pharetra eros porttitor. Fusce eu  massa magna, quis imperdiet massa. Suspendisse sed libero et purus  dignissim sagittis vel eget dolor. In viverra ornare tellus, venenatis  sagittis orci fringilla sit amet. Ut venenatis nunc ac ligula venenatis  placerat. Donec auctor bibendum sagittis. Nam ac risus nec augue  molestie dignissim. Cras sed nisl leo.<\/p>","messageformat":"1","messagetrust":"0","attachment":"","totalscore":"0","mailnow":"0","firstname":"Imanol","lastname":"Urra Ruiz"}}},"4":{"id":"4","course":"3","forum":"2","name":"Propostes de la Viquip\u00e8dia comentades","firstpost":"7","userid":"2","groupid":"-1","assessed":"1","timemodified":"1275047121","usermodified":"2","timestart":"0","timeend":"0","firstname":"Imanol","lastname":"Urra Ruiz","posts":{"7":{"id":"7","discussion":"4","parent":"0","userid":"2","created":"1275047121","modified":"1275047121","mailed":"0","subject":"Propostes de la Viquip\u00e8dia comentades","message":"<p>Hola a tothom,<br \/><br \/>a hores dara ja he respost a totes les vostres  propostes de nous articles a la Viquip\u00e8dia. Encara queden temes per  tancar, per\u00f2 la majoria de vosaltres ja el teniu for\u00e7a definit.<br \/><br \/>El  proper pas \u00e9s que editeu la p\u00e0gina del Viquiprojecte de la llengua o  lleng\u00fces amb les quals escriureu i creeu un ella\u00e7 cap cap vostre article  encara queno estigui creat (en aquest cas apareixer\u00e0 en vermell). Per  fer-ho, editeu el par\u00e0graf anomenat \"En construcci\u00f3\". Daquesta manera  tots plegats tindrem una visi\u00f3 global dels articles amb els que esteu  treballant.<br \/><br \/>Abans deditar el Viquiprojecte, cal que us  identifiqueu amb el vostre nom dusuari a la Viquip\u00e8dia. Si no en teniu,  creeu un compte, un per cada llengua.<br \/><br \/>He activat al Moodle totes  les tasques relacionades amb la Viquip\u00e8dia. Recordeu que a finals de  mes heu denllestir la revisi\u00f3 en grup de larticle assignat. A cada  tasca trobareu una descripci\u00f3 detallada de la informaci\u00f3 que heu  denviar i dels aspectes que savaluaran. Com a novetat, he introdu\u00eft  una altra activitat de votaci\u00f3 popular mitjan\u00e7ant la qual els dos equips  amb una nota mitjana major obtindran un positiu addicional de cara al  recompte.<br \/><br \/>Fixeu-vos amb els terminis !<\/p>","messageformat":"1","messagetrust":"0","attachment":"","totalscore":"0","mailnow":"0","firstname":"Imanol","lastname":"Urra Ruiz"}}}}},{"id":"4","course":"3","module":"15","instance":"2","section":"13","idnumber":"","added":"1274458521","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Informe CBIR-QBE","modname":"resource","intro":"<p>informe CBIRQBE<\/p>","introformat":"1","resourceformat":"odt","url":"http:\/\/localhost\/~index02\/moodbile\/moodle\/pluginfile.php\/28\/resource_content\/2\/informeCBIRQBE.odt"},{"id":"11","course":"3","module":"15","instance":"3","section":"13","idnumber":"","added":"1275472715","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Hoja de Ayuda - HTML5 ","modname":"resource","intro":"<p>Hoja de ayuda de HTML5, con sus nuevas etiquetas y atributos<\/p>","introformat":"1","resourceformat":"pdf","url":"http:\/\/localhost\/~index02\/moodbile\/moodle\/pluginfile.php\/35\/resource_content\/1\/WOORK - HTML 5 Cheat Sheet.pdf"},{"id":"6","course":"3","module":"1","instance":"2","section":"14","idnumber":"","added":"1274486696","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Entrega Documentacion","modname":"assignment","intro":"<p>Entrega de la documentacion. Recordad, letra 12, Comic Sans y negrita.<\/p>","introformat":"1","timedue":"1280534400","timeavailable":"1274486400","timemodified":"1274486696"},{"id":"7","course":"3","module":"18","instance":"1","section":"15","idnumber":"","added":"1274494437","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Google Font Directory","modname":"url","intro":"<p>Directorio con una coleccion de fuentes, junto a la forma de implementarlo en la Web<\/p>","introformat":"1","url":"http:\/\/code.google.com\/webfonts"}]},{"id":"2","shortname":"Coms AV","category":"1","fullname":"Comunicacions Audiovisuals","startdate":"1273708800","summary":"<p>Lobjectiu de lassignatura \u00e9s proporcionar a lestudiant els  coneixements que li permetin entendre els sistemes de transmissi\u00f3  d\u00e0udio i v\u00eddeo. En aquest context es presenten els sistemes anal\u00f2gics i  digitals de transmissi\u00f3 d\u00e0udio i v\u00eddeo, posant especial \u00e8mfasi en les  solucions t\u00e8cniques per a televisi\u00f3. Es presenten tamb\u00e9 en forma  introduct\u00f2ria els principals sistemes de transmissi\u00f3 f\u00edsica (cable,  sat\u00e8l\u00b7lit i terrestre).<\/p>","format":"topics","timecreated":"1273678077","timemodified":"1275414509","lastcoursesync":1276036755,"modules":[{"id":"1","course":"2","module":"7","instance":"1","section":"1","idnumber":null,"added":"1273678350","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"News forum","modname":"forum","intro":"General news and announcements","introformat":"0","discussions":{"1":{"id":"1","course":"2","forum":"1","name":"Ejemplo de lo que devuelve ahora el get_courses","firstpost":"1","userid":"2","groupid":"-1","assessed":"1","timemodified":"1274578094","usermodified":"2","timestart":"0","timeend":"0","firstname":"Imanol","lastname":"Urra Ruiz","posts":{"1":{"id":"1","discussion":"1","parent":"0","userid":"2","created":"1274476835","modified":"1274476835","mailed":"0","subject":"Ejemplo de lo que devuelve ahora el get_courses","message":"<p>blalblalblallba<\/p>","messageformat":"1","messagetrust":"0","attachment":"","totalscore":"0","mailnow":"0","firstname":"Imanol","lastname":"Urra Ruiz"},"2":{"id":"2","discussion":"1","parent":"1","userid":"2","created":"1274498102","modified":"1274498102","mailed":"0","subject":"Re: Ejemplo de lo que devuelve ahora el get_courses","message":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tristique,  urna vitae euismod vehicula, odio quam ullamcorper magna, sit amet  aliquet nunc arcu ac justo. Aenean aliquet neque vitae nisi fringilla  non ullamcorper massa euismod. Nam adipiscing lorem eu lacus lacinia ac  ultricies nunc bibendum. Donec sagittis elit sit amet diam ultricies  lacinia. Pellentesque non ipsum ligula. Ut gravida mollis augue a  tempus. Donec bibendum est a massa condimentum tristique. Ut ornare quam  at urna cursus at pharetra neque ultricies. Praesent hendrerit sodales  molestie. Aliquam faucibus tortor sit amet est imperdiet elementum.  Aenean et dui ante. Maecenas mi elit, tempus sit amet luctus eleifend,  blandit eu velit. Mauris ipsum eros, ultricies vitae convallis et,  scelerisque sit amet quam.<\/p>","messageformat":"1","messagetrust":"0","attachment":"","totalscore":"0","mailnow":"0","firstname":"Imanol","lastname":"Urra Ruiz"},"3":{"id":"3","discussion":"1","parent":"1","userid":"2","created":"1274578027","modified":"1274578027","mailed":"0","subject":"Re: Ejemplo de lo que devuelve ahora el get_courses","message":"<p>Donec eleifend convallis massa quis tempus. Nullam eget nisi diam.  Pellentesque habitant morbi tristique senectus et netus et malesuada  fames ac turpis egestas. Phasellus nisl arcu, consequat et rutrum vitae,  accumsan non mi. Praesent est metus, posuere non congue lobortis,  imperdiet nec metus. Praesent viverra commodo augue, sed pharetra dolor  dictum quis. Nunc feugiat mi at diam accumsan scelerisque. Nam venenatis  ultrices ante, in eleifend lacus laoreet ac. In ac elit arcu, at auctor  urna. Donec facilisis lorem eu nisi rutrum vehicula. In cursus libero  id risus hendrerit vel blandit elit ullamcorper. Vestibulum facilisis  vehicula nulla, malesuada auctor ante cursus et. Nunc accumsan faucibus  viverra. Mauris mollis accumsan magna, non pellentesque velit  pellentesque lobortis. Praesent lacinia tincidunt sapien sit amet  ultricies. Vivamus leo augue, mollis a aliquam eu, ullamcorper et lacus.  Curabitur non sem lacus, sit amet tristique neque. Donec enim enim,  volutpat in ornare in, tincidunt sit amet ipsum.<\/p>","messageformat":"1","messagetrust":"0","attachment":"","totalscore":"0","mailnow":"0","firstname":"Imanol","lastname":"Urra Ruiz"},"4":{"id":"4","discussion":"1","parent":"3","userid":"2","created":"1274578094","modified":"1274578094","mailed":"0","subject":"Re: Ejemplo de lo que devuelve ahora el get_courses","message":"<p>Aliquam eu dui nibh, in vulputate libero. In in arcu tortor, quis  malesuada odio. Etiam dignissim facilisis urna eu sagittis. Nulla  pharetra semper neque at sollicitudin.<\/p>","messageformat":"1","messagetrust":"0","attachment":"","totalscore":"0","mailnow":"0","firstname":"Imanol","lastname":"Urra Ruiz"}}}}},{"id":"10","course":"2","module":"7","instance":"3","section":"3","idnumber":"","added":"1275415739","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Ajuda sobre adquisici\u00f3 i reproducci\u00f3","modname":"forum","intro":"<p>F\u00f2rum de consulta sobre el tema dadquisici\u00f3 i reproducci\u00f3.<\/p>","introformat":"1","discussions":null},{"id":"2","course":"2","module":"15","instance":"1","section":"1","idnumber":"60","added":"1273678508","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Fist PDF File","modname":"resource","intro":"","introformat":"1","resourceformat":"pdf","url":"http:\/\/localhost\/~index02\/moodbile\/moodle\/pluginfile.php\/21\/resource_content\/2\/Memoria.pdf"},{"id":"12","course":"2","module":"15","instance":"4","section":"2","idnumber":"","added":"1275473090","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"How to build offline WebApp","modname":"resource","intro":"<p>Tutorial de como crear manifiesto<\/p>","introformat":"1","resourceformat":"pdf","url":"http:\/\/localhost\/~index02\/moodbile\/moodle\/pluginfile.php\/36\/resource_content\/1\/presentatie-090322075153-phpapp02.pdf"},{"id":"5","course":"2","module":"1","instance":"1","section":"2","idnumber":"50","added":"1274476321","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Entrega de coleccion de problemas","modname":"assignment","intro":"<p>Por cada ejercicio correcto, 1 punto.<\/p>\r\n<p>Si se superan los 7 puntos se obtendra un aprovado<\/p>","introformat":"1","timedue":"1275081000","timeavailable":"1274476200","timemodified":"1274480557"},{"id":"9","course":"2","module":"1","instance":"3","section":"3","idnumber":"","added":"1275414008","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Registre al curs del MoodleTSC","modname":"assignment","intro":"<p>Cal que tots els estudiants es registrin al curs al Moodle del TSC. Si  teniu problemes, aneu al laboratori durant els horaris de lliure  assist\u00e8ncia.<\/p>","introformat":"1","timedue":"1276018500","timeavailable":"1275413700","timemodified":"1275414008"},{"id":"8","course":"2","module":"6","instance":"1","section":"2","idnumber":"","added":"1274497327","score":"0","indent":"0","visible":"1","visibleold":"1","groupmode":"0","groupingid":"0","groupmembersonly":"0","completion":"0","completiongradeitemnumber":null,"completionview":"0","completionexpected":"0","availablefrom":"0","availableuntil":"0","showavailability":"0","name":"Folder test","modname":"folder","intro":"<p>Primer directorio de prueba.<\/p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tristique,  urna vitae euismod vehicula, odio quam ullamcorper magna, sit amet  aliquet nunc arcu ac justo. Aenean aliquet neque vitae nisi fringilla  non ullamcorper massa euismod. Nam adipiscing lorem eu lacus lacinia ac  ultricies nunc bibendum. Donec sagittis elit sit amet diam ultricies  lacinia. Pellentesque non ipsum ligula. Ut gravida mollis augue a  tempus. Donec bibendum est a massa condimentum tristique. Ut ornare quam  at urna cursus at pharetra neque ultricies. Praesent hendrerit sodales  molestie. Aliquam faucibus tortor sit amet est imperdiet elementum.  Aenean et dui ante. Maecenas mi elit, tempus sit amet luctus eleifend,  blandit eu velit. Mauris ipsum eros, ultricies vitae convallis et,  scelerisque sit amet quam.<\/p>","introformat":"1"}]}])'; //JSONP
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
        
        $json = $_POST["callback"] . "(" . json_encode($posts) . ")"; //JSONP
        
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