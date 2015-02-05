<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--  Mobile Viewport Fix -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Title</title>
    <meta name="description" content="the description">

    <!-- css -->
    <link href="<?php echo URL; ?>public/css/style.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <!-- JavaScript -->
    <script src="<?php echo URL; ?>public/js/application.js"></script>
</head>

<body>
    <div class="header">
        <h1>The header</h1>
        <img src="<?php echo URL; ?>public/img/demo-image.png" />
        <div class="navigation">
            <ul>
                <!-- same like "home" or "home/index" -->
                <li><a href="<?php echo URL; ?>">home</a></li>
                <li><a href="<?php echo URL; ?>home/link1">Link1</a></li>
                <li><a href="<?php echo URL; ?>home/link2">Link2</a></li>
                <li><a href="<?php echo URL; ?>slink3">Link3</a></li>
            </ul>
        </div> <!-- end nav -->
    </div> <!-- end header -->
