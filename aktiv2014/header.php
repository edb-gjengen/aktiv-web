<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="no" lang="no">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow" />
    <meta name="x-stylesheet-directory" content="<?php bloginfo( 'stylesheet_directory' ); ?>" />
    <meta name="x-inside-api-nonce" content="<?php echo wp_create_nonce( 'inside-api' ); ?>" />
    <meta name="x-user-meta-nonce" content="<?php echo wp_create_nonce( 'user-meta' ); ?>" />
    <meta name="x-username" content="<?php $user = wp_get_current_user(); echo $user->user_login; ?>" />
    <meta name="x-user-id" content="<?php echo $user->ID; ?>" />

    <meta name="x-siteurl" content="<?php echo get_option('siteurl'); ?>" />
    <link rel="icon" type="image/png" href="favicon.ico" />
    <link rel="alternate" type="application/rss+xml" href="<?php echo get_feed_url(); ?>" title="aktiv.neuf.no - Nyheter" />

    <link href="<?php bloginfo( 'stylesheet_directory' ); ?>/dist/styles/main.css" rel="stylesheet" type="text/css" />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,700,800,600' rel='stylesheet' type='text/css'>
    <?php wp_head(); ?>
    <script src="<?php bloginfo( 'stylesheet_directory' ); ?>/dist/scripts/vendor.js"></script>

    <script src="<?php bloginfo( 'stylesheet_directory' ); ?>/dist/scripts/main.js"></script>

    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>

<body <?php neuf_body_class(); ?>>
    <!-- Google analytics -->
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-52914-18', 'auto');
  ga('send', 'pageview');

    </script>
    <header id="site-header">
        <div id="header-container">
            <div class="site-title">
                <a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home" class="logo"><?php bloginfo('name') ?></a>
                </span>
            </div>

            <?php get_template_part( 'menu' ); ?>

        </div>
    </header><!--  #site-header -->

