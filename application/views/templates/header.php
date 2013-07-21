<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $title ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
		
		<!-- TODO: Include fontawesome IE7 css file only in IE7 -->
		<?php echo $static['css'] ?>
		<?php echo $static['js'] ?>
    </head>
    <body class="<?php echo $bodyClass ?>">
        <!--[if lt IE 7]>
            <p class="chromeframe">
                You are using an <strong>outdated</strong> browser. 
                Please <a href="http://browsehappy.com/">upgrade your browser</a> 
                or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> 
                to improve your experience.
            </p>
        <![endif]-->

        <?php
            if(isset($msg)){
                $status = $msg['status'] == 1 ? 'success' : 'error';
                $msg = $msg['msg'];
                print <div class={'msg msg-' . $status}>{"{{" . $msg . "}}"}</div>;
            }
        ?>
    
    <div id="autooffer">
        <?php if($renderMenu){
            echo
            <header>
                <ui:wrapper>
                    <h1>AutoOffer</h1>
                    <nav id="main">
                        <ci:mainMenu />
                    </nav>
                </ui:wrapper>
            </header>;
        }
        ?>
        <div role="main" id="main">