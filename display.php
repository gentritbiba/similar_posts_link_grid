<?php 
function display_similar_posts($current_post_id, $num_posts = 5) {
  $all_posts = get_posts(['exclude' => [$current_post_id]]); // Get other posts
  $similarity_scores = calculate_tfidf_similarity(get_post($current_post_id), $all_posts);

  $html = '<h3>Similar Posts:</h3><ul>';
  $count = 0;
  foreach ($similarity_scores as $post_id => $score) {
      if ($count >= $num_posts) break;
      $html .= '<li><a href="' . get_permalink($post_id) . '">' . get_the_title($post_id) . '</a></li>';
      $count++;
  }
  $html .= '</ul>';

  return $html;
}