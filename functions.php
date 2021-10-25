<?php
namespace CECSGrad\Theme;

define( 'CECS_GRAD_DIR', trailingslashit( get_stylesheet_directory() ) );


// Theme foundation
include_once CECS_GRAD_DIR . 'includes/config.php';
include_once CECS_GRAD_DIR . 'includes/meta.php';
include_once CECS_GRAD_DIR . 'includes/custom-post-types.php';
include_once CECS_GRAD_DIR . 'includes/custom-rss-layout.php';

// Add other includes to this file as needed.
