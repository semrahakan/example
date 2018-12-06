<?php
namespace App\Repositories;

use App\Repositories\TranslatedInterface as TranslatedInterface;
use App\Translated;

class TranslatedRepository implements TranslatedInterface
{
    public $translated_text;

    function __construct(Translated $translated_text) {
    $this->translated_text = $translated_text;
   
    }

    public function storeT($target, $translatedText, $textStore)
    {
        return $this->translated_text->create(
            ['langCode' => $target,
                'translated_text' => $translatedText,
                'text_to_be_translated_id' => $textStore['id']
            ]
            );
    }

    public function storeTranslate($target, $translatedText, $id){

        return $this->translated_text->create(
            ['langCode' => $target,
                'translated_text' => $translatedText,
                'text_to_be_translated_id' => $id
            ]
            );
    }


    public function getAllTranslated(){
        return $this->translated_text->with('text');
    }
}