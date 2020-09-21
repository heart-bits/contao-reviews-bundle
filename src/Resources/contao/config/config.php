<?php

/**
 * Back end modules
 */
array_push($GLOBALS['BE_MOD']['content']['companies']['tables'], 'tl_reviews');

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['review'] = array
(
    'review'   => 'Heartbits\ContaoReviews\Review'
);
