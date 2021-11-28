<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Events extends Model
{
    protected $table = 'events';
    public $timestamps = true;
    public $appends = ['banner_image', 'start_date_formatted', 'end_date_formatted'];

    public function getBannerImageAttribute() {
    	return url(Storage::url( 'uploads/' ) . @$this->attributes['banner']);
    }

    public function getStartDateFormattedAttribute() {
    	return date('d-m-Y', strtotime(@$this->attributes['start_date']));
    }

    public function getEndDateFormattedAttribute() {
    	return date('d-m-Y', strtotime(@$this->attributes['end_date']));
    }
    
}
