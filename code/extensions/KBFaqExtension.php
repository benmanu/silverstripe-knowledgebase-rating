<?php

class KBFaqExtension extends DataExtension
{
    /**
     * @param \FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Get rating for this FAQ
        $ratings = Rating::get()
            ->filter([
                'ObjectClass' => 'FAQ',
                'ObjectID'    => $this->owner->ID,
            ])
            ->sort('ID', 'Desc');

        $fields->addFieldToTab('Root.Ratings',
            $grid = new GridField('Ratings', 'Ratings', $ratings)
        );

        $exportButton = new GridFieldExportButton('buttons-before-left');

        $exportButton->setExportColumns([
            'ConvertScoreToText' => 'Does this article answer your question?',
            'Comment'            => 'Comment',
        ]);

        $config = $grid->getConfig();
        $config->addComponents(
            new GridFieldButtonRow('before'),
            $exportButton,
            new GridFieldDetailForm(),
            new GridFieldViewButton(),
            new GridFieldDeleteAction()
        );
        $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);

        $dataColumns->setDisplayFields([
            'ConvertScoreToText' => 'Does this article answer your question?',
            'TruncatedComment'   => 'Comment',
        ]);
    }

}
