<?php

class FAQRatingForm extends Form
{
    /**
     * Constructor for the Knowledge FAQ question and answer rating Form.
     *
     * @param Controller $controller
     * @param string $name
     */
    public function __construct($controller, $name = 'FAQRatingForm')
    {
        $fields = FieldList::create(
            OptionsetField::create(
                'Rating',
                'Rating',
                [
                    '1' => 'Yes',
                    '0' => 'No',
                ]
            )->addExtraClass('faq-rating-form__optionset'),
            TextareaField::create(
                'Comment',
                'Comment'
            )
                ->addExtraClass('faq-rating-form__comment')
                ->setAttribute('placeholder', 'What could we do better?'),
            HiddenField::create('FAQID', 'FAQID', $controller->request->param('ID'))
        );

        $actions = FieldList::create(
            FormAction::create(
                'doFAQRating',
                'Send'
            )->addExtraClass('faq-rating-form__submit')
        );

        $this->addExtraClass('faq-rating-form');

        parent::__construct($controller, $name, $fields, $actions);

        // allow custom template to be used based on class name
        $this->setTemplate('FAQRatingForm');
    }

    /**
     * Handler for the form submission.
     *
     * @param array $data
     * @param Form $form
     *
     * @return \HTMLText|string
     * @throws \ValidationException
     */
    public function doFAQRating($data, $form)
    {
        $score = isset($data['Rating']) ? (int)$data['Rating'] : '';
        $class = 'FAQ';

        $id = isset($data['FAQID']) ? (int)$data['FAQID'] : '';

        // check we have all the params
        if (!class_exists($class) || !$id || (!$faq = FAQ::get()->byID($id))) {
            $sessionMsg = 'Sorry, there was an error rating this FAQ';

            if ($this->request->isAjax()) {
                $response = new SS_HTTPResponse();
                $response->setBody(json_encode([
                    'json' => true,
                    'status'  => 'error',
                    'message' => $sessionMsg,
                ]))->setStatusCode(403);

                $response->addHeader("Content-type", "application/json");

                return $response;
            } else {
                $this->sessionMessage( $sessionMsg,'bad');
                return $this->controller->redirectBack();
            }

        }

        $rateableService = new RateableService();
        $ratingRecord = $rateableService->userGetRating($class, $id);

        $comment = isset($data['Comment']) ? Convert::raw2sql($data['Comment']) : '';

        // If the record exist for current session, save the data to same the record.
        if ($ratingRecord) {

            if (isset($data['Rating'])) {
                $ratingRecord->Score = $score;
            }

            if ($comment) {
                $ratingRecord->Comment = $comment;
            }

            $ratingRecord->write();
        } else {
            // create the rating
            $rating = Rating::create([
                'Score'       => $score,
                'ObjectID'    => $id,
                'ObjectClass' => $class,
                'Comment'     => $comment,
            ]);

            $rating->write();
        }

        $templateData = [
            'Message' => 'Thanks for rating!',
        ];

        // Create a hook for add custom message and custom data for template
        if ($this->hasMethod('UpdateRatingData')) {
            $templateData = $this->UpdateRatingData($data, $form, $score, $comment, $templateData);
        }

        if ($this->request->isAjax()) {
            return $this->customise($templateData)->renderWith('FAQRatingFormSuccess');
        } else {
            return $this->customise($templateData)->renderWith('Page', 'FAQRatingFormSuccess');
        }

    }
}
