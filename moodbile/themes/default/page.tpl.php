<!DOCTYPE html>
<html <?php print $manifest; ?>>
    <head>
        <meta charset="utf-8" />
	    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
        <meta content="yes" name="apple-mobile-web-app-capable"/>
	    
	    <title><?php print $title; ?></title>
        <?php print $styles; ?>
    </head>

    <body>
        <div id="container">
            <div id="content" class="content">
                <header>
                    <h1><a class="sitename" href="#"><?php print $title; ?></a></h1>
                </header>
                <nav id="breadcrumb"><?php print $breadcrumb; ?></nav>
                <nav id="toolbar"></nav>
                <div id="wrapper"></div>
                <footer></footer>
            </div>
        </div>
        <?php print $scripts; ?>
  </body>
</html>