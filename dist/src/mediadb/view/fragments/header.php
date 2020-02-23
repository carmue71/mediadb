<!doctype html>
<html lang="en">
  <head>
    <title><?php print (isset($this)&& isset($this->pageTitle))?$this->pageTitle:"Charly's MediaDB ".VERSION;?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/fontawesome.min.css" integrity="sha256-mM6GZq066j2vkC2ojeFbLCcjVzpsrzyMVUnRnEQ5lGw=" crossorigin="anonymous" />
   	<link rel="stylesheet" type="text/css" href="<?php print WWW?>css/star-rating-svg.css">
   	<link rel="stylesheet" href="<?php print WWW?>css/mystyle.css" />
	<link rel="icon" href="<?php print WWW?>img/favicon.ico" type="image/x-icon">
  </head>
  <body<?php if ( isset($bodymodifier) ) print $bodymodifier;?>>