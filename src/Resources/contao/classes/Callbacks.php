<?php

namespace Heartbits\ContaoReviews;

use Contao\Database;

class Callbacks extends \Backend
{
    /**
     * Get all visible reviews
     *
     * @return array
     */
    public function getReviewOptions()
    {
        $reviews = Database::getInstance()->prepare("SELECT id, title FROM tl_reviews WHERE invisible='' ORDER BY title ASC")->execute()->fetchAllAssoc();
        $options = array();
        if (!empty($reviews) && is_array($reviews)) {
            foreach ($reviews as $review) {
                $options[$review['id']] = $review['title'];
            }
        }
        return $options;
    }
}
