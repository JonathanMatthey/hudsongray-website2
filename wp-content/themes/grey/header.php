<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

	<title>hudsongray - join us</title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>

	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-stellar.min.js"></script>
    <link rel="stylesheet" href="<? echo get_bloginfo('template_directory'); ?>/style.css">
    <link rel="stylesheet" href="<? echo get_bloginfo('template_directory'); ?>/css/hudsongray.css">
</head>

<body <?php body_class(); ?>>
<nav class="nav">
  <!-- Toggle Switch -->
  <button class="nav__toggle">
    <span class="toggle__bar"></span>
    <span class="toggle__bar"></span>
    <span class="toggle__bar"></span>
  </button>
  <!-- End Switch -->

  <div class="nav__inner">
    <a href="/" class="nav__logo">hudsongray</a>

    <menu class="nav__menu">
      <li class="nav__menu__item">
        <a href="/studios/" class="u-link-ohra">Studios</a>
      </li>
      <li class="nav__menu__item">
        <a href="/work/" class="u-link-mare">Work</a>
      </li>
      <li class="nav__menu__item">
        <a href="/play/" class="u-link-honey">Play</a>
      </li>
      <li class="nav__menu__item">
        <a href="/ppp/" class="u-link-jeezz">PPP</a>
      </li>
      <li class="nav__menu__item">
        <a href="/blog/" class="u-link-piglet <?php if (strpos("$_SERVER[REQUEST_URI]",'/blog/')!==FALSE) echo 'active'; ?>">Blog </a>
      </li>
      <li class="nav__menu__item nav__menu__item--with-label">
        <a href="/find-us/" class="u-link-rain">
          <span class="link__label">Find us</span>
          <span class="link__desc">London, Malmö, New York</span>
        </a>
      </li>
      <li class="nav__menu__item nav__menu__item--with-label">
        <a href="/join-us/" class="u-link-pot <?php if (strpos("$_SERVER[REQUEST_URI]",'/join-us/')!==FALSE) echo 'active'; ?>">
          <span class="link__label">Join us</span>
          <span class="link__desc">See open positions</span>
        </a>
      </li>
      <li class="nav__menu__contact">
        <ul class="nav__menu__contact__list">
          <li class="nav__menu__contact__list__item">
            <a href="mailto:hello@hudsongray.com" class="u-link-rain">hello@hudsongray.com</a>
          </li>

          <li class="nav__menu__contact__list__item nav__menu__contact__list__item--studio" data-studio="nyc">
            <a href="tel:+1(212)518-4900">+1 (212) 518-4900</a>
          </li>

          <li class="nav__menu__contact__list__item nav__menu__contact__list__item--studio" data-studio="london">
            <a href="tel:+44(0)2076130433">+44 (0) 207 613 0433</a>
          </li>

          <li class="nav__menu__contact__list__item nav__menu__contact__list__item--studio" data-studio="malmo">
            <a href="tel:+46(0)40330480">+46 (0) 40 330 480</a>
          </li>
        </ul>
      </li>
    </menu>
  </div>
</nav>

	<div id="main" class="site-main">
