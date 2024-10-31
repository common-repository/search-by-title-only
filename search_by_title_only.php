<?php

/*
  Plugin Name: WordPress Search By Title Only
  Description: This plugin limits the search to post titles only.
  Version: 1.0.3
  Author: Mahesh Prajapati
  Author URI: www.idomit.com
 */

function sbtonly_filter($search, $wp_query) {
    global $wpdb;
    if (empty($search))
        return $search;
    $q = $wp_query->query_vars;
    $n = !empty($q['exact']) ? '' : '%';
    $search = $searchand = '';
    foreach ((array) $q['search_terms'] as $term) {
        $term = esc_sql(like_escape($term));
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }
    if (!empty($search)) {
        $search = " AND ({$search}) ";
        if (!is_user_logged_in())
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
}

add_filter('posts_search', 'sbtonly_filter', 500, 2);

/* End of plugin */
?>