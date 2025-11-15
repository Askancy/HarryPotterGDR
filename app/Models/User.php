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

    /**
     * Get user's notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Create a notification for this user.
     */
    public function notify($type, $title, $message, $icon = null, $link = null, $data = null)
    {
        return Notification::create([
            'user_id' => $this->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'link' => $link,
            'data' => $data
        ]);
    }

    /**
     * Get user's skills.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('level', 'experience')
            ->withTimestamps();
    }

    /**
     * Get user's skills with details.
     */
    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * Get current location.
     */
    public function currentLocation()
    {
        return $this->belongsTo(Location::class, 'current_location_id');
    }

    /**
     * Get visited locations.
     */
    public function visitedLocations()
    {
        return $this->belongsToMany(Location::class, 'user_locations')
            ->withPivot('visit_count', 'first_visited_at', 'last_visited_at')
            ->withTimestamps();
    }

    /**
     * Get shops owned by user.
     */
    public function ownedShops()
    {
        return $this->hasMany(LocationShop::class, 'current_owner_id');
    }

    /**
     * Get user's random events.
     */
    public function randomEvents()
    {
        return $this->hasMany(UserRandomEvent::class);
    }

    /**
     * Get active random events.
     */
    public function activeEvents()
    {
        return $this->randomEvents()
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    /**
     * Add experience and handle level up.
     */
    public function addExperience($amount)
    {
        $this->current_exp += $amount;
        $this->total_exp_earned += $amount;

        $leveledUp = false;

        // Check for level up
        while ($this->current_exp >= $this->required_exp) {
            $this->current_exp -= $this->required_exp;
            $this->level++;
            $leveledUp = true;

            // Get level rewards
            $reward = LevelReward::where('level', $this->level)->first();

            if ($reward) {
                $this->skill_points += $reward->skill_points;
                $this->money += $reward->money_reward;

                // Notify user of level up
                $this->notify(
                    'level_up',
                    'Livello Aumentato!',
                    "Congratulazioni! Hai raggiunto il livello {$this->level}!" .
                    ($reward->title ? " Ora sei un {$reward->title}!" : ""),
                    'fa-trophy',
                    '/profile',
                    [
                        'level' => $this->level,
                        'skill_points' => $reward->skill_points,
                        'money' => $reward->money_reward
                    ]
                );
            }

            // Calculate next level exp requirement (exponential growth)
            $this->required_exp = (int) (100 * pow(1.5, $this->level - 1));
        }

        $this->save();

        return $leveledUp;
    }

    /**
     * Travel to a location.
     */
    public function travelTo(Location $location)
    {
        if (!$location->canBeAccessedBy($this)) {
            return false;
        }

        $this->current_location_id = $location->id;
        $this->save();

        // Track visit
        $visit = $this->visitedLocations()->where('location_id', $location->id)->first();

        if ($visit) {
            $visit->pivot->visit_count++;
            $visit->pivot->last_visited_at = now();
            $visit->pivot->save();
        } else {
            $this->visitedLocations()->attach($location->id, [
                'visit_count' => 1,
                'first_visited_at' => now(),
                'last_visited_at' => now()
            ]);
        }

        return true;
    }

    /**
     * Enter an inn.
     */
    public function enterInn(LocationShop $inn)
    {
        if (!$inn->isInn()) {
            return false;
        }

        $visitor = InnVisitor::updateOrCreate(
            [
                'shop_id' => $inn->id,
                'user_id' => $this->id
            ],
            [
                'entered_at' => now(),
                'last_activity' => now()
            ]
        );

        return $visitor;
    }

    /**
     * Leave an inn.
     */
    public function leaveInn(LocationShop $inn)
    {
        InnVisitor::where('shop_id', $inn->id)
            ->where('user_id', $this->id)
            ->delete();
    }

}
