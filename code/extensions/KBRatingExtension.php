<?php

class KBRatingExtension extends DataExtension
{

    /**
     * @var array
     */
    private static $db = [
        "Comment" => "Text",
    ];

    /**
     * @param \FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName('ObjectID');
        $fields->removeByName('ObjectClass');
        $fields->removeByName('SessionID');
        $fields->removeByName('MemberID');
    }

    /**
     * Convert rating score value to text
     *
     * @return string
     */
    public function getConvertScoreToText()
    {
        return ($this->owner->Score == 1) ? 'Yes' : 'No';
    }

    /**
     * @return string
     */
    public function TruncatedComment()
    {
        return $this->owner->dbObject('Comment')->LimitCharacters(120);
    }
}
