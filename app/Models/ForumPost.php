<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    protected $table = 'forum_post';

    public function last_topic()
    {
      return $this->belongsTo('App\Models\ForumTopic','id_topic','id');
    }

    public function last_author()
    {
      return $this->belongsTo('App\Models\User','id_user','id');
    }
}
