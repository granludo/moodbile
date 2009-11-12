<!DOCTYPE HTML>
<html><head>

    
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
        <meta content="yes" name="apple-mobile-web-app-capable">
	    
	    <title>Moodbile</title>
        <link href="testclient_files/reset.css" media="all" rel="stylesheet" type="text/css">
<link href="testclient_files/moodbile.css" media="all" rel="stylesheet" type="text/css">    <style type="text/css" charset="utf-8">/* See license.txt for terms of usage */

#firebugBody {
    position: fixed;
    top:0;
    left:0;
    margin:0;
    padding:0;
    width:1px;
    height:1px;
    overflow:visible;
}

.firebugCanvas {
    position:fixed;
    top: 0;
    left: 0;
    display:none;
    border: 0 none;
    margin: 0;
    padding: 0;
    outline: 0;
}

.firebugHighlight {
    z-index: 2147483647;
    position: absolute;
    background-color: #3875d7;
    margin: 0;
    padding: 0;
    outline: 0;
    border: 0 none;
}

.firebugLayoutBoxParent {
    z-index: 2147483647;
    position: absolute;
    background-color: transparent;
    border-top: 0 none;
    border-right: 1px dashed #BBBBBB;
    border-bottom: 1px dashed #BBBBBB;
    border-left: 0 none;
    margin: 0;
    padding: 0;
    outline: 0;
}

.firebugRuler {
    position: absolute;
    margin: 0;
    padding: 0;
    outline: 0;
    border: 0 none;
}

.firebugRulerH {
    top: -15px;
    left: 0;
    width: 100%;
    height: 14px;
    background: url(chrome://firebug/skin/rulerH.png) repeat-x;
    border-top: 1px solid #BBBBBB;
    border-right: 1px dashed #BBBBBB;
    border-bottom: 1px solid #000000;
}

.firebugRulerV {
    top: 0;
    left: -15px;
    width: 14px;
    height: 100%;
    background: url(chrome://firebug/skin/rulerV.png) repeat-y;
    border-left: 1px solid #BBBBBB;
    border-right: 1px solid #000000;
    border-bottom: 1px dashed #BBBBBB;
}

.overflowRulerX > .firebugRulerV {
    left: 0;
}

.overflowRulerY > .firebugRulerH {
    top: 0;
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
.firebugLayoutBox {
    margin: 0;
    padding: 0;
    border: 0 none;
    outline: 0;
}

.firebugLayoutBoxOffset {
    z-index: 2147483647;
    position: absolute;
    opacity: 0.8;
}

.firebugLayoutBoxMargin {
    background-color: #EDFF64;
}

.firebugLayoutBoxBorder {
    background-color: #666666;
}

.firebugLayoutBoxPadding {
    background-color: SlateBlue;
}

.firebugLayoutBoxContent {
    background-color: SkyBlue;
}

/*.firebugHighlightGroup .firebugLayoutBox {
    background-color: transparent;
}

.firebugHighlightBox {
    background-color: Blue !important;
}*/

.firebugLayoutLine {
    z-index: 2147483647;
    background-color: #000000;
    opacity: 0.4;
    margin: 0;
    padding: 0;
    outline: 0;
    border: 0 none;
}

.firebugLayoutLineLeft,
.firebugLayoutLineRight {
    position: fixed;
    width: 1px;
    height: 100%;
}

.firebugLayoutLineTop,
.firebugLayoutLineBottom {
    position: absolute;
    width: 100%;
    height: 1px;
}

.firebugLayoutLineTop {
    margin-top: -1px;
    border-top: 1px solid #999999;
}

.firebugLayoutLineRight {
    border-right: 1px solid #999999;
}

.firebugLayoutLineBottom {
    border-bottom: 1px solid #999999;
}

.firebugLayoutLineLeft {
    margin-left: -1px;
    border-left: 1px solid #999999;
}
</style></head><body>
        <header>
            <h1><a id="sitename" href="#">Moodbile</a></h1>
        </header>
        <div id="wrapper">
            <!-- El contenido se ira añadiendo aqui mediante JSON -->
        <section style="display: none;" class="courses"><div class="course"><a href="#" id="0">Curso 0</a></div><div class="course"><a href="#" id="1">Curso 1</a></div><div class="course"><a href="#" id="2">Curso 2</a></div></section><section style="display: block;" class="resources-0"><div class="0"><a href="#">Recurso 0</a></div><div class="1"><a href="#">Recurso 1</a></div><div class="2"><a href="#">Recurso 2</a></div></section><section style="display: none;" class="grade-0"><div class="Test"><a href="#">Test<em>7.5</em></a></div><div class="Ejercicio Sostenibilidad"><a href="#">Ejercicio Sostenibilidad<em>7.5</em></a></div></section><section style="display: none;" class="events-0"><div class="event 0"><a href="#">Entrega trabajo<em>01/10/2009</em></a></div><div class="event 1"><a href="#">Entrega trabajo 2<em>02/10/2009</em></a></div></section></div>
        <nav style="">
            <ul><li class="0" id="courses"><a href="#">Cursos</a></li><li class="0 active" id="resources"><a href="#">Recursos</a></li><li class="0" id="forum"><a href="#">Foros</a></li><li class="0" id="events"><a href="#">Eventos</a></li><li class="0" id="grade"><a href="#">Calificaciones</a></li></ul>        </nav>
        <script type="text/javascript" src="testclient_files/jquery.js"></script>
<script type="text/javascript" src="testclient_files/moodbile_002.js"></script>
<script type="text/javascript" src="testclient_files/fixed.js"></script>
<script type="text/javascript" src="testclient_files/courses.js"></script>
<script type="text/javascript" src="testclient_files/resources.js"></script>
<script type="text/javascript" src="testclient_files/forum.js"></script>
<script type="text/javascript" src="testclient_files/events.js"></script>
<script type="text/javascript" src="testclient_files/grade.js"></script>
<script type="text/javascript" src="testclient_files/moodbile.js"></script>  </body></html>