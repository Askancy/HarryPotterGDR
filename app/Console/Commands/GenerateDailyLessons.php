<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subject;
use App\Models\DailyLesson;
use App\Models\WeatherCondition;
use App\Models\SchoolEvent;
use Carbon\Carbon;

class GenerateDailyLessons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:generate {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily lessons schedule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') ? Carbon::parse($this->argument('date')) : Carbon::tomorrow();

        $this->info("Generando lezioni per {$date->format('d/m/Y')}...");

        // Check if there are events that suspend lessons
        $events = SchoolEvent::where('start_date', '<=', $date)
            ->where(function($q) use ($date) {
                $q->where('end_date', '>=', $date)->orWhereNull('end_date');
            })
            ->where('suspend_lessons', true)
            ->get();

        if ($events->isNotEmpty()) {
            $this->warn("Lezioni sospese per evento: {$events->first()->name}");
            return;
        }

        // Check if lessons already exist for this date
        $existingCount = DailyLesson::whereDate('date', $date)->count();
        if ($existingCount > 0) {
            if (!$this->confirm("Esistono già {$existingCount} lezioni per questa data. Vuoi eliminarle e rigenerarle?")) {
                return;
            }
            DailyLesson::whereDate('date', $date)->delete();
        }

        // Get active subjects
        $subjects = Subject::where('is_active', true)->get();

        if ($subjects->isEmpty()) {
            $this->error('Nessuna materia attiva trovata!');
            return;
        }

        // Select 2 random subjects for the day
        $selectedSubjects = $subjects->random(min(2, $subjects->count()));

        $slots = ['morning', 'afternoon'];
        $created = 0;

        foreach ($selectedSubjects as $index => $subject) {
            $slot = $slots[$index] ?? 'morning';

            // Random bonus for variety
            $expMultiplier = rand(80, 120) / 100; // 0.8 to 1.2
            $pointsMultiplier = rand(80, 120) / 100;

            // Special bonus (10% chance)
            $specialBonus = rand(1, 100) <= 10 ? 'double_skill_points' : null;

            DailyLesson::create([
                'date' => $date,
                'subject_id' => $subject->id,
                'slot' => $slot,
                'max_participants' => rand(30, 50),
                'current_participants' => 0,
                'exp_multiplier' => $expMultiplier,
                'points_multiplier' => $pointsMultiplier,
                'special_bonus' => $specialBonus,
            ]);

            $created++;
            $slotLabel = $slot === 'morning' ? 'Mattina' : 'Pomeriggio';
            $this->line("✓ Creata lezione: {$subject->name} ({$slotLabel})");
        }

        // Generate weather
        $this->generateWeather($date);

        $this->info("\n✅ Generate {$created} lezioni per {$date->format('d/m/Y')}!");
    }

    /**
     * Generate weather for the date.
     */
    protected function generateWeather($date)
    {
        // Determine season
        $month = $date->month;
        $season = match(true) {
            in_array($month, [3, 4, 5]) => 'spring',
            in_array($month, [6, 7, 8]) => 'summer',
            in_array($month, [9, 10, 11]) => 'autumn',
            default => 'winter'
        };

        // Check if weather already exists
        if (WeatherCondition::whereDate('date', $date)->whereNull('location_id')->exists()) {
            return;
        }

        // Generate weather for main areas
        $areas = ['hogwarts-castle', 'forbidden-forest', 'quidditch-pitch', 'hogsmeade'];

        foreach ($areas as $area) {
            WeatherCondition::generateForSeason($season, null, $area);
        }

        $this->line("✓ Generato meteo per {$date->format('d/m/Y')}");
    }
}
