<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumTopic extends Model
{
    protected $table = 'forum_topic';

    protected $fillable = [
      'deleted', 'locked', 'notable',
    ];

    public function getStatus(){
      if($this->deleted){
        return '<i class="far fa-trash-alt"></i>';
      }
      if($this->locked){
        return '<i class="fas fa-lock"></i>';
      }
      else{
        return '<i class="fas fa-comments"></i>';
      }
    }
}
