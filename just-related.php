<?php

/*
Plugin Name: Just Related
Plugin URI: http://appstore.probashitimes.com/
Description: This is a simple plugin to show related posts' list just below your post.
Version: 1.0.1
Author: A S M Sayem
Author URI: https://profiles.wordpress.org/asmsayem/
License: GPLv2
*/

/* 
Copyright (C) 2015 A S M Sayem

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
/*Adding Filter to Hook up with WordPress*/
add_filter('the_content', 'MA_related_post');

/*Creating a function to show related posts as list*/
function MA_related_post($content)
{
if (!is_singular('post')){ //Checking if the post is in single page
	return $content;
}
//Getting category IDs in an array
	$categories = get_the_terms(get_the_ID(), 'category');
	$categoriesIds = array();
	
	foreach($categories as $category) {
		$categoriesIds[] = $category->term_id;
	}
	//Arranging related posts randomly in a loop to show as a list
	$loop = new WP_Query(array(
		'category_in' => $categoriesIds,
		'posts_per_page' => 5,
		'post__not_in' => array(get_the_ID()),
		'orderby' => 'rand'
	));
	if($loop->have_posts()){
		$content .= 'More Related Posts:<br/><ul>'; //Creating related posts list with HTML to show.
		while($loop->have_posts()){
			$loop->the_post();
			$content .= '<li><a href="'.get_permalink().'"> '.get_the_title().'</a></li>';
		}
		$content .= '</ul>';
	}
	wp_reset_query();
	return $content;
}
?>