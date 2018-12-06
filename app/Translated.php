<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translated extends Model
{
	public $table = "translated";
	
    protected $fillable = ['langCode', 'translated_text', 'text_to_be_translated_id'];

    public function text()
    {
        return $this->hasMany('App\TextToBeTranslated', 'id', 'text_to_be_translated_id');
    }
}
