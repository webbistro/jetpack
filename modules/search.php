<?php
/**
 * Module Name: Search
 * Module Description: A drop-in replacement for your front-end site search, powered by WordPress.com Elasticsearch!
 * Sort Order: 50
 * First Introduced: 3.?
 * Requires Connection: No
 * Auto Activate: No
 * Module Tags: Other
 */

require_once( dirname( __FILE__ ) . '/search/class.search.php' );
Jetpack_Search::do_the_things();