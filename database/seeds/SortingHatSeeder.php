<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SortingHatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pulisci le tabelle
        DB::table('Poll_answers')->delete();
        DB::table('Poll_questions')->delete();

        $questions = [
            [
                'name' => 'Quale qualità apprezzi di più in te stesso?',
                'answers' => [
                    ['answer' => 'Il coraggio e l\'audacia', 'value' => 1],  // Grifondoro
                    ['answer' => 'L\'astuzia e l\'ambizione', 'value' => 2], // Serpeverde
                    ['answer' => 'La lealtà e la pazienza', 'value' => 3],    // Tassorosso
                    ['answer' => 'L\'intelligenza e la saggezza', 'value' => 4], // Corvonero
                ]
            ],
            [
                'name' => 'Cosa temi di più?',
                'answers' => [
                    ['answer' => 'Il disonore e la codardia', 'value' => 1],
                    ['answer' => 'Il fallimento e la mediocrità', 'value' => 2],
                    ['answer' => 'La solitudine e l\'ingiustizia', 'value' => 3],
                    ['answer' => 'L\'ignoranza e la stupidità', 'value' => 4],
                ]
            ],
            [
                'name' => 'Quale animale ti rappresenta meglio?',
                'answers' => [
                    ['answer' => 'Un leone coraggioso', 'value' => 1],
                    ['answer' => 'Un serpente astuto', 'value' => 2],
                    ['answer' => 'Un tasso leale', 'value' => 3],
                    ['answer' => 'Un\'aquila saggia', 'value' => 4],
                ]
            ],
            [
                'name' => 'In una situazione difficile, come reagisci?',
                'answers' => [
                    ['answer' => 'Affronto il pericolo a testa alta', 'value' => 1],
                    ['answer' => 'Cerco il modo più vantaggioso per uscirne', 'value' => 2],
                    ['answer' => 'Aiuto prima gli altri poi penso a me', 'value' => 3],
                    ['answer' => 'Analizzo la situazione e trovo una soluzione intelligente', 'value' => 4],
                ]
            ],
            [
                'name' => 'Quale materia magica ti affascina di più?',
                'answers' => [
                    ['answer' => 'Difesa contro le Arti Oscure', 'value' => 1],
                    ['answer' => 'Pozioni e Arti Oscure', 'value' => 2],
                    ['answer' => 'Erbologia e Cura delle Creature Magiche', 'value' => 3],
                    ['answer' => 'Incantesimi e Trasfigurazione', 'value' => 4],
                ]
            ],
            [
                'name' => 'Come preferiresti essere ricordato?',
                'answers' => [
                    ['answer' => 'Come un eroe coraggioso', 'value' => 1],
                    ['answer' => 'Come una persona potente e influente', 'value' => 2],
                    ['answer' => 'Come un amico fedele e gentile', 'value' => 3],
                    ['answer' => 'Come una mente brillante', 'value' => 4],
                ]
            ],
            [
                'name' => 'Quale dono della morte sceglieresti?',
                'answers' => [
                    ['answer' => 'La Bacchetta di Sambuco (potere)', 'value' => 1],
                    ['answer' => 'La Pietra della Resurrezione (controllo della morte)', 'value' => 2],
                    ['answer' => 'Il Mantello dell\'Invisibilità (protezione per me e gli altri)', 'value' => 3],
                    ['answer' => 'Nessuno, sono solo leggende da studiare', 'value' => 4],
                ]
            ],
            [
                'name' => 'Cosa faresti se trovassi un libro di magia potente ma pericolosa?',
                'answers' => [
                    ['answer' => 'Lo userei per proteggere i miei amici', 'value' => 1],
                    ['answer' => 'Lo studierei per acquisire potere', 'value' => 2],
                    ['answer' => 'Lo consegnerei a qualcuno di fidato', 'value' => 3],
                    ['answer' => 'Lo studierei per comprenderne i segreti', 'value' => 4],
                ]
            ],
            [
                'name' => 'Qual è il tuo ambiente ideale?',
                'answers' => [
                    ['answer' => 'Un campo di Quidditch pieno di azione', 'value' => 1],
                    ['answer' => 'Le segrete del castello, piene di mistero', 'value' => 2],
                    ['answer' => 'I giardini di Hogwarts, circondato dalla natura', 'value' => 3],
                    ['answer' => 'La biblioteca, piena di libri da studiare', 'value' => 4],
                ]
            ],
            [
                'name' => 'Quale incantesimo vorresti imparare per primo?',
                'answers' => [
                    ['answer' => 'Expecto Patronum - per proteggere chi amo', 'value' => 1],
                    ['answer' => 'Legilimens - per leggere i pensieri altrui', 'value' => 2],
                    ['answer' => 'Episkey - per curare le ferite', 'value' => 3],
                    ['answer' => 'Accio - per richiamare oggetti con precisione', 'value' => 4],
                ]
            ],
        ];

        foreach ($questions as $q) {
            $questionId = DB::table('Poll_questions')->insertGetId([
                'name' => $q['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($q['answers'] as $a) {
                DB::table('Poll_answers')->insert([
                    'id_question' => $questionId,
                    'answer' => $a['answer'],
                    'value' => $a['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Sorting Hat questions and answers seeded successfully!');
    }
}
