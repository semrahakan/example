<?php
namespace App\Repositories;

use App\Repositories\TextInterface as TextInterface;
use App\TextToBeTranslated;
use App\Translated;

class TextRepository implements TextInterface
{
    public $text;

    function __construct(TextToBeTranslated $text,Translated $translated
      ) {
           $this->text = $text;
           $this->translated = $translated;
   
    }

    public function storeT($source, $text)
    {
      $same = $this->getTextName($text);
       //to prevent duplicate content for db table: text_to_be_translated
       if (count($same)>0) {
          foreach ($same as $key => $value) {
              return $value->id;
          }
       }else{
            return $this->text->create(
                ['langCode' => $source,
                  'text' => $text]
                );
       }
    }
    public function getTextName($text){
        return $this->text->where('text', '=', $text)->get();
    }
    public function getText(){
        return $this->text->with('translated')->get();
    }
    public function getTextID($id){
        return $this->text->where('id','=',$id)->get();
    }
    //a word can have multiple translated word with different lang codes it is a restriction to get the exact translated word from the langcode
    public function getTextWithName($text, $target){
      return $this->translated->select('translated.*')
                    ->join('text_to_be_translated', 'text_to_be_translated.id', '=', 'translated.text_to_be_translated_id')
                    ->where('text_to_be_translated.text', '=', $text)
                    ->where('translated.langCode', '=', $target)
                    ->with('text')
                    ->get()->toArray();

    }
    public function getTextsWithArray($list, $target){
     
      $text='';
      foreach ($list as $key => $value) {
       $text = $value;
      }
      return $this->translated->select('translated.*')
                    ->join('text_to_be_translated', 'text_to_be_translated.id', '=', 'translated.text_to_be_translated_id')
                    ->where('text_to_be_translated.text', '=', $text)
                    ->where('translated.langCode', '=', $target)
                    ->with('text')
                    ->get()->toArray();
    }
}