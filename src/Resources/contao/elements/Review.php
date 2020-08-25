<?php

namespace Heartbits\ContaoReviews;

use Contao\ContentElement;
use Contao\Database;
use Contao\BackendTemplate;
use Contao\System;
use Contao\FilesModel;
use Contao\StringUtil;

class Review extends ContentElement
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_review';


    /**
     * Generate the content element
     */
    protected function compile()
    {
        // Get selected review/s from database
        if ($this->useSingleReview && $this->review_select) {
            $reviewData = Database::getInstance()->prepare("SELECT * FROM tl_reviews WHERE id=?")->execute($this->review_select)->fetchAllAssoc();
        } else {
            $limit = "";
            if (intval($this->limitReviews) > 0) {
                $limit = " LIMIT " . intval($this->limitReviews);
            }
            $reviewData = Database::getInstance()->prepare("SELECT * FROM tl_reviews WHERE invisible='' ORDER BY tstamp DESC" . $limit)->execute()->fetchAllAssoc();
        }

        // Push selected review/s to template
        if (TL_MODE == 'BE') {
            $this->Template = new BackendTemplate('be_wildcard');
            $title = '';
            if (!empty($reviewData)) {
                $reviewCount = count($reviewData);
                $i = 1;
                foreach ($reviewData as $review) {
                    if ($reviewCount === $i) {
                        $title .= $review['title'] . '<br>';
                    } else {
                        $title .= $review['title'] . ',<br>';
                    }
                    $i++;
                }
            }
            $this->Template->title = $title;
        } else {
            $container = System::getContainer();
            $rootDir = $container->getParameter('kernel.project_dir');
            System::loadLanguageFile('tl_reviews');
            $arrReviews = [];
            if (!empty($reviewData)) {
                $i = 0;
                foreach ($reviewData as $review) {
                    foreach ($review as $key => $value) {
                        if ($key === 'author') {
                            $contact = Database::getInstance()->prepare("SELECT lastname, firstname, singleSRC, company FROM tl_contacts WHERE id=?")->execute($value);
                            $company = Database::getInstance()->prepare("SELECT title, href FROM tl_companies WHERE id=?")->execute($contact->company);
                            $arrReviews[$i][$key]['lastname'] = $contact->lastname;
                            $arrReviews[$i][$key]['firstname'] = $contact->firstname;
                            $arrReviews[$i][$key]['company_name'] = $company->title;
                            $arrReviews[$i][$key]['company_href'] = $company->href;

                            // Generate picture
                            if ($contact->singleSRC !== '') {
                                $objFile = FilesModel::findByUuid($contact->singleSRC);
                                $path = $objFile->path;
                                if ($objFile !== null || is_file(System::getContainer()->getParameter('kernel.project_dir') . '/' . $path)) {
                                    $picture = $container
                                        ->get('contao.image.picture_factory')
                                        ->create($rootDir . '/' . $path, StringUtil::deserialize($this->size)[2]);
                                    $data = [
                                        'picture' => [
                                            'img' => $picture->getImg($rootDir),
                                            'sources' => $picture->getSources($rootDir),
                                        ]
                                    ];
                                    $arrReviews[$i][$key]['singleSRC'] = $data;
                                }
                            }
                        } else {
                            $arrReviews[$i][$key] = $value;
                        }
                    }
                    $i++;
                }
                $this->Template->size = StringUtil::deserialize($this->size)[2];
                $this->Template->reviews = $arrReviews;
            }
        }
    }
}
