<?php

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['reviews'] = array(
    'tables' => array('tl_reviews')
);

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['review'] = array
(
    'review'   => 'Heartbits\ContaoReviews\Review'
);
