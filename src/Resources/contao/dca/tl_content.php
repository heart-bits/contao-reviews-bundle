<?php

/**
 * Palettes
 */
array_push($GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'], 'useSingleReview');
$GLOBALS['TL_DCA']['tl_content']['palettes']['review'] = '{type_legend},type,limitReviews,size,useSingleReview;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

/**
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['useSingleReview'] = 'review_select';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['review_select'] = array
(
    'exclude' => true,
    'options_callback' => array('Heartbits\ContaoReviews\Callbacks', 'getReviewOptions'),
    'inputType' => 'select',
    'eval' => array(
        'includeBlankOption' => true,
        'tl_class' => 'w50'
    ),
    'sql' => 'int(100) unsigned NULL',
);

$GLOBALS['TL_DCA']['tl_content']['fields']['useSingleReview'] = array
(
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array(
        'submitOnChange'=>true,
        'tl_class' => 'clr'
    ),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['limitReviews'] = array
(
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array(
        'rgxp' => 'natural',
        'tl_class' => 'w50 clr'
    ),
    'sql'                     => "smallint(5) unsigned NOT NULL default 0"
);
