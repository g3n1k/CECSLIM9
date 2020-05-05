<!-- Page Title
============================================= -->
<title><?php echo $page_title; ?></title>
<?php
    $_uri = $_SERVER["SERVER_NAME"]. uri_check($_SERVER["REQUEST_URI"]) ? '':$_SERVER["REQUEST_URI"];
?>

<!-- Meta
============================================= -->
<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, post-check=0, pre-check=0" />
<meta http-equiv="Expires" content="Sat, 26 Jul 1997 05:00:00 GMT" />

<?php if(isset($_GET['p']) && ($_GET['p'] == 'show_detail')): ?>
<meta name="description" content="<?php echo substr($notes,0,152).'...'; ?>">
<meta name="keywords" content="<?php echo $subject; ?>">
<?php else: ?>
<meta name="description" content="<?php echo $page_title; ?>">
<meta name="keywords" content="<?php echo $sysconf['library_subname']; ?>">
<?php endif; ?>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
<meta name="generator" content="<?php echo SENAYAN_VERSION ?>">
<meta name="theme-color" content="#000">

<!-- Opengraph
============================================= -->
<meta property="og:locale" content="<?php echo str_replace('-','_',$sysconf['default_lang']); ?>"/>
<meta property="og:type" content="book"/>
<meta property="og:title" content="<?php echo $page_title; ?>"/>
<?php if(isset($_GET['p']) && ($_GET['p'] == 'show_detail')): ?>
<meta property="og:description" content="<?php echo substr($notes,0,152).'...'; ?>"/>
<?php else: ?>
<meta property="og:description" content="<?php echo $sysconf['library_subname']; ?>"/>
<?php endif; ?>
<meta property="og:url" content="//<?php echo $_uri; ?>"/>
<meta property="og:site_name" content="<?php echo $sysconf['library_name']; ?>"/>
<?php if(isset($_GET['p']) && ($_GET['p'] == 'show_detail')): ?>
<meta property="og:image" content="//<?php echo $_SERVER["SERVER_NAME"].SWB.$image_src ?>"/>
<?php else: ?>
<meta property="og:image" content="//<?php echo $_SERVER["SERVER_NAME"].SWB.$sysconf['template']['dir']; ?>/default/img/logo.png"/>
<?php endif; ?>

<!-- Twitter
============================================= -->
<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="//<?php echo $_uri; ?>"/>
<meta name="twitter:title" content="<?php echo $page_title; ?>"/>
<?php if(isset($_GET['p']) && ($_GET['p'] == 'show_detail')): ?>
<meta property="twitter:image" content="//<?php echo $_SERVER["SERVER_NAME"].SWB.$image_src ?>"/>
<?php else: ?>
<meta property="twitter:image" content="//<?php echo $_SERVER["SERVER_NAME"].SWB.$sysconf['template']['dir']; ?>/default/img/logo.png"/>
<?php endif; ?>



<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Lato:400,400i|Poppins:300,400,500,600" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway" rel="stylesheet">
<!-- Favicon -->
<link rel="icon" href="favicon.png" type="image/x-icon" />
<link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
<!-- Mobile Metas -->
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Vendor CSS -->
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/reset.css">
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/ion-icons/ionicons.min.css">
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/owl-slider/owl.carousel.css">
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/slideshow/slideshow.css">
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/lightbox/lity.min.css">
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/css/jquery.autocomplete.css">
<!-- Theme CSS -->
<link id="theme" rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/css/theme4.css">
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/css/loading.css">
<link rel="stylesheet" href="<?php echo $sysconf['template']['dir']; ?>/custom/assets/css/loading-btn.css">
<style media="screen">
  @media screen and (max-width: 500px) {
    .autocomplete-suggestions {
        max-height: 130px;
    }
  }
  @media screen and (min-width: 501px) {
    .autocomplete-suggestions {
        max-height: 300px;
    }
  }
  @media screen and (min-width: 600px) {
    #whatsapp {
      visibility: hidden;
      display: none;
    }
  }
  .owl-controls {
    position: absolute;
    top: -310px;
    left: 0;
    transform: translate(0%,-100%);
    width: 100%;
  }

  .owl-nav {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    width: 100%;
  }
    .owl-prev {
      float: left;
    }
    .owl-prev .kiri {
      color: white;
    }
    .owl-next .kanan {
      color: white;
    }
    .owl-next {
      color:white;
      float: right;
    }
  }

  .owl-dots {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
  }
  .pagination {

    display: inline-block;
  }

  .pagination a {
      font-family: Poppins, sans-serif;
      text-decoration: none;
  }
  dl {
    width: 100%;
    overflow: hidden;
    padding: 0;
    margin: 0;
    font-size: 12px;
  }
  dt {
    float: left;
    width: 50%;
    /* adjust the width; make sure the total of both is 100% */
    padding: 0 10px 0 0;
    margin: 0 0 15px 0;
    text-align: right;
  }
  dd {
    float: left;
    width: 50%;
    /* adjust the width; make sure the total of both is 100% */
    padding: 0;
    margin: 0 0 15px 0;
    font-weight: bold;
    font-size: 14px;
  }
</style>

<link rel="canonical" href="//<?php echo $_uri; ?>" />
<?php echo $metadata; ?>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/jquery-2.2.1.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/matchHeight-min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/contact/validator.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/contact/contact.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/pace.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/headroom/headroom.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/owl-slider/owl.carousel.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/slideshow/anime.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/slideshow/imagesloaded.pkgd.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/slideshow/main.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/parallax/materialize.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/lightbox/lity.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/tabs/jquery.tabslet.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/masonry.pkgd.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/js/main.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/js/jquery.autocomplete.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/modernizr.js"></script>
<?php echo $metadata; ?>

<?php if($sysconf['captcha']['type'] == 'recaptcha'){ ?>
<!-- recaptcha -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function onSubmit(token) {
        document.getElementById("i-recaptcha").submit();
    }
</script>
<?php } ?>