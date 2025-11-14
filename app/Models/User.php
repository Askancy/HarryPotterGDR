<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 'name', 'surname', 'birthday', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sex()
    {
      if ($this->sex == '0') {
        return 'Maschio';
      } else {
        return 'Femmina';
      }
    }

    public function age()
    {
        return Carbon::parse($this->attributes['birthday'])->age;
    }

    public function house()	{
			return $this->belongsTo('App\Models\Team', 'team', 'id');
		}
    // 1 - Grifondoro
    // 2 - Serpeverde
    // 3 - Corvonero
    // 4 - Tassorosso
    public function team()
	  {
        if ($this->team == "1") {
          return 'Grifondoro';
        } elseif ($this->team == "2") {
          return 'Serpeverde';
        } elseif ($this->team == "3") {
          return 'Corvonero';
        } elseif ($this->team == "4") {
          return 'Tassorosso';
        } else {
          return 'Non hai una casa!';
        }
	  }

    public function team_img()
	  {
        if ($this->team == "1") {
          return 'upload/icon/grifo.gif';
        } elseif ($this->team == "2") {
          return 'upload/icon/tasso.gif';
        } elseif ($this->team == "3") {
          return 'upload/icon/corvo.gif';
        } elseif ($this->team == "4") {
          return 'upload/icon/serpe.gif';
        }
	  }

    public function avatar()
    {
      if($this->avatar == 'default.jpg')  {
        return 'default.jpg';
      } elseif(!file_exists(public_path('upload/user/'.$this->avatar))) {
        return 'default.jpg';
      } else {
        return $this->avatar;
      }
    }

    public function role()
    {
      if($this->group == 1) {
        return 'Moderatore';
      } elseif($this->group == 2) {
        return 'Amministratore';
      } else {
        return 'Utente';
      }
    }

}
