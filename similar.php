<?php 
function calculate_tfidf_similarity($current_post, $all_posts) {
    // Preprocessing
    $current_words = preprocess_text($current_post->post_title . ' ' . $current_post->post_content);
    $all_documents = [];

    foreach ($all_posts as $post) {
        $all_documents[] = preprocess_text($post->post_title . ' ' . $post->post_content);
    }

    // Calculate TF-IDF scores (Simplified Implementation)
    $tfidf_scores = calculate_tfidf($current_words, $all_documents);

    // Map scores back to posts and sort
    $similarity_scores = [];
    foreach ($all_posts as $index => $post) {
        $similarity_scores[$post->ID] = $tfidf_scores[$index];
    }
    arsort($similarity_scores);

    return $similarity_scores;
}

// VERY BASIC preprocessing
function preprocess_text($text) {
    $text = strtolower($text); // Lowercase
    $text = preg_replace('/[^a-z0-9 ]/', '', $text); // Remove punctuation (basic)
    $words = explode(' ', $text); 
    return $words;
}

function calculate_tfidf($term, $documents) {
    $doc_count = count($documents);
    $word_counts = []; // Store how many times each word appears in each document 

    // Calculate Term Frequencies (TF) 
    foreach ($documents as $index => $doc) {
        foreach ($doc as $word) {
            $word_counts[$index][$word] = isset($word_counts[$index][$word]) ? $word_counts[$index][$word] + 1 : 1;
        }
    }

    // Calculate Inverse Document Frequencies (IDF)
    $idfs = [];
    foreach ($word_counts as $doc_words) {
        foreach ($doc_words as $word => $count) {
            $docs_with_word = 0;
            foreach ($word_counts as $other_doc) {
                if (isset($other_doc[$word])) {
                    $docs_with_word++;
                }
            }
            $idfs[$word] = log($doc_count / $docs_with_word);  
        }
    }

    // Calculate TF-IDF scores
    $tfidf_scores = [];
    foreach ($documents as $index => $doc) {
        foreach ($doc as $word) {
            $tf = $word_counts[$index][$word] / count($doc);
            $tfidf_scores[$index] += $tf * $idfs[$word];
        }
    }

    return $tfidf_scores;
}