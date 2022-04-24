<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objects extends Model

  {
  	protected $table = "Objects";


    public function type()
	  {
        if ($this->type == "1") {
          return 'Animali';
        } elseif ($this->type == "2") {
          return 'Bacchette';
        } elseif ($this->type == "3") {
          return 'Scope';
        } elseif ($this->type == "4") {
          return 'Pozioni';
        } elseif ($this->type == "5") {
          return 'Vari';
        }
	  }

  }
