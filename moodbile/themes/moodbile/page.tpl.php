<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
        <meta content="yes" name="apple-mobile-web-app-capable"/>
	    
	    <title><?php print $title; ?></title>
        <?php print $styles; ?>
    </head>

    <body>
        <div id="container">
            <div id="content" class="content"> <!-- TODO: Cambiar clases content<->wrapper y todo lo que conlleva -->
                <!--<div class="box dragy">Hola</div>-->
                <header>
                    <h1><a id="sitename" href="#"><?php print $title; ?></a></h1>
                    <div class="avatar"></div>
                </header>
                <nav id="breadcrumb">
                <?php print $breadcrumb; ?>
                </nav>
                <div id="wrapper">
                    <!-- El contenido se ira añadiendo aqui mediante JSON -->
                </div>
            </div>
        </div>
        <nav id="toolbar">
                    <?php print $menu_items; ?>
        </nav>
        <?php print $scripts; ?>
  </body>
</html>