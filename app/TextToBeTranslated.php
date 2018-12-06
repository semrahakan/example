<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TextToBeTranslated extends Model
{
	public $table = "text_to_be_translated";
    protected $fillable = ['langCode', 'text'];

    public function translated()
    {
        return $this->belongsTo('App\Translated', 'text_id', 'id');
    }
}
