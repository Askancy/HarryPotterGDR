<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cerimonia dello Smistamento - Hogwarts GDR</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=MedievalSharp&display=swap');

        .font-magic {
            font-family: 'Cinzel', serif;
        }

        .font-medieval {
            font-family: 'MedievalSharp', cursive;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.5); }
            50% { box-shadow: 0 0 40px rgba(255, 215, 0, 0.8); }
        }

        .glow-effect {
            animation: glow 2s ease-in-out infinite;
        }

        .sorting-hat {
            filter: drop-shadow(0 10px 30px rgba(139, 69, 19, 0.7));
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-indigo-900 overflow-x-hidden">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" x-data="sortingCeremony()">

        <!-- Candele fluttuanti (background) -->
        <div class="fixed inset-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-2 h-8 bg-gradient-to-b from-yellow-200 to-orange-500 rounded-full opacity-70 animate-float"></div>
            <div class="absolute top-40 right-20 w-2 h-8 bg-gradient-to-b from-yellow-200 to-orange-500 rounded-full opacity-70 animate-float" style="animation-delay: 0.5s;"></div>
            <div class="absolute bottom-40 left-1/4 w-2 h-8 bg-gradient-to-b from-yellow-200 to-orange-500 rounded-full opacity-70 animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute top-60 right-1/3 w-2 h-8 bg-gradient-to-b from-yellow-200 to-orange-500 rounded-full opacity-70 animate-float" style="animation-delay: 1.5s;"></div>
        </div>

        <div class="max-w-4xl w-full">

            <!-- Welcome Screen -->
            <div x-show="currentStep === 'welcome'" x-cloak
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">

                <div class="text-center mb-12">
                    <h1 class="text-6xl md:text-7xl font-magic font-bold text-yellow-400 mb-4 drop-shadow-2xl">
                        Benvenuto a Hogwarts
                    </h1>
                    <p class="text-2xl text-gray-300 font-medieval">
                        La Cerimonia dello Smistamento ti attende...
                    </p>
                </div>

                <div class="bg-black bg-opacity-50 backdrop-blur-lg rounded-2xl p-8 md:p-12 border-4 border-yellow-600 glow-effect">

                    <!-- Sorting Hat Image -->
                    <div class="flex justify-center mb-8">
                        <div class="relative">
                            <i class="fas fa-hat-wizard text-9xl text-yellow-700 sorting-hat animate-float"></i>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-32 h-32 bg-yellow-400 rounded-full opacity-20 blur-3xl"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-magic font-bold text-white mb-4">Il Cappello Parlante</h2>
                        <p class="text-lg text-gray-300 leading-relaxed">
                            "Oh, potresti appartenere ai <span class="text-red-400 font-bold">Grifondoro</span>,<br>
                            dove gli audaci dimorano nei cuori,<br>
                            il loro coraggio, l'ardire e la nobiltà<br>
                            li distinguono dagli altri.<br><br>

                            Forse nei <span class="text-green-400 font-bold">Serpeverde</span>,<br>
                            dove troverai i tuoi veri amici,<br>
                            quella gente astuta usa qualsiasi mezzo<br>
                            per raggiungere i suoi fini.<br><br>

                            O magari nei saggi <span class="text-blue-400 font-bold">Corvonero</span>,<br>
                            se hai la mente pronta,<br>
                            dove quelli di spirito e cultura<br>
                            troveranno sempre i loro simili.<br><br>

                            Oppure potresti essere dei <span class="text-yellow-400 font-bold">Tassorosso</span>,<br>
                            dove sono giusti e leali,<br>
                            quelli dei Tassorosso sono sinceri<br>
                            e non temono la fatica."
                        </p>
                    </div>

                    <div class="text-center">
                        <button @click="startSorting()"
                                class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-600 to-yellow-800 hover:from-yellow-500 hover:to-yellow-700 text-gray-900 font-bold text-xl rounded-full shadow-2xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-hat-wizard mr-3 text-2xl group-hover:rotate-12 transition-transform"></i>
                            Inizia lo Smistamento
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quiz Questions -->
            <div x-show="currentStep === 'quiz'" x-cloak
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform translate-x-full"
                 x-transition:enter-end="opacity-100 transform translate-x-0">

                <div class="bg-black bg-opacity-50 backdrop-blur-lg rounded-2xl p-8 md:p-12 border-4 border-yellow-600">

                    <!-- Progress Bar -->
                    <div class="mb-8">
                        <div class="flex justify-between text-sm text-gray-400 mb-2">
                            <span>Domanda <span x-text="currentQuestion + 1"></span> di <span x-text="questions.length"></span></span>
                            <span x-text="Math.round((currentQuestion / questions.length) * 100) + '%'"></span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 h-3 rounded-full transition-all duration-500"
                                 :style="'width: ' + ((currentQuestion / questions.length) * 100) + '%'"></div>
                        </div>
                    </div>

                    <!-- Question -->
                    <template x-if="questions[currentQuestion]">
                        <div>
                            <h3 class="text-2xl font-magic font-bold text-white mb-6 text-center" x-text="questions[currentQuestion].question"></h3>

                            <div class="space-y-4">
                                <template x-for="(answer, index) in questions[currentQuestion].answers" :key="index">
                                    <button @click="selectAnswer(answer)"
                                            class="w-full text-left p-6 bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 border-2 border-gray-600 hover:border-yellow-500 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl group">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 rounded-full bg-yellow-600 bg-opacity-20 flex items-center justify-center mr-4 group-hover:bg-opacity-40 transition-colors">
                                                <span class="text-2xl font-bold text-yellow-400" x-text="String.fromCharCode(65 + index)"></span>
                                            </div>
                                            <span class="text-lg text-white font-medium" x-text="answer.text"></span>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Sorting Result -->
            <div x-show="currentStep === 'result'" x-cloak
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 transform scale-50"
                 x-transition:enter-end="opacity-100 transform scale-100">

                <div class="text-center">

                    <!-- Dramatic Reveal -->
                    <div class="mb-8">
                        <div class="relative inline-block">
                            <i class="fas fa-hat-wizard text-9xl sorting-hat animate-float"
                               :class="{
                                   'text-red-600': sortedHouse === 'Grifondoro',
                                   'text-green-600': sortedHouse === 'Serpeverde',
                                   'text-blue-600': sortedHouse === 'Corvonero',
                                   'text-yellow-600': sortedHouse === 'Tassorosso'
                               }"></i>
                        </div>
                    </div>

                    <div class="bg-black bg-opacity-50 backdrop-blur-lg rounded-2xl p-8 md:p-12 border-4 glow-effect"
                         :class="{
                             'border-red-600': sortedHouse === 'Grifondoro',
                             'border-green-600': sortedHouse === 'Serpeverde',
                             'border-blue-600': sortedHouse === 'Corvonero',
                             'border-yellow-600': sortedHouse === 'Tassorosso'
                         }">

                        <h2 class="text-4xl font-magic font-bold mb-4"
                            :class="{
                                'text-red-500': sortedHouse === 'Grifondoro',
                                'text-green-500': sortedHouse === 'Serpeverde',
                                'text-blue-500': sortedHouse === 'Corvonero',
                                'text-yellow-500': sortedHouse === 'Tassorosso'
                            }">
                            Il Cappello ha Parlato!
                        </h2>

                        <h1 class="text-6xl md:text-8xl font-magic font-bold mb-8"
                            :class="{
                                'text-red-400': sortedHouse === 'Grifondoro',
                                'text-green-400': sortedHouse === 'Serpeverde',
                                'text-blue-400': sortedHouse === 'Corvonero',
                                'text-yellow-400': sortedHouse === 'Tassorosso'
                            }"
                            x-text="sortedHouse.toUpperCase()"></h1>

                        <!-- House Description -->
                        <div class="mb-8">
                            <template x-if="sortedHouse === 'Grifondoro'">
                                <div>
                                    <p class="text-xl text-gray-300 mb-4">
                                        "Appartieni ai coraggiosi, dove albergano gli audaci di cuore!"
                                    </p>
                                    <div class="flex justify-center space-x-4 mb-4">
                                        <div class="text-center">
                                            <i class="fas fa-shield-alt text-4xl text-red-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Coraggio</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-fist-raised text-4xl text-red-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Ardimento</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-crown text-4xl text-red-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Nobiltà</p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="sortedHouse === 'Serpeverde'">
                                <div>
                                    <p class="text-xl text-gray-300 mb-4">
                                        "Qui troverai i tuoi veri amici, gente astuta che usa ogni mezzo per i suoi fini!"
                                    </p>
                                    <div class="flex justify-center space-x-4 mb-4">
                                        <div class="text-center">
                                            <i class="fas fa-brain text-4xl text-green-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Astuzia</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-chess text-4xl text-green-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Ambizione</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-gem text-4xl text-green-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Determinazione</p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="sortedHouse === 'Corvonero'">
                                <div>
                                    <p class="text-xl text-gray-300 mb-4">
                                        "Se hai mente pronta, qui troverai i tuoi simili, spiriti saggi e colti!"
                                    </p>
                                    <div class="flex justify-center space-x-4 mb-4">
                                        <div class="text-center">
                                            <i class="fas fa-book text-4xl text-blue-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Saggezza</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-lightbulb text-4xl text-blue-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Intelligenza</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-star text-4xl text-blue-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Creatività</p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="sortedHouse === 'Tassorosso'">
                                <div>
                                    <p class="text-xl text-gray-300 mb-4">
                                        "Qui sono giusti e leali, sinceri e non temono la fatica!"
                                    </p>
                                    <div class="flex justify-center space-x-4 mb-4">
                                        <div class="text-center">
                                            <i class="fas fa-heart text-4xl text-yellow-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Lealtà</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-hands-helping text-4xl text-yellow-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Dedizione</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-balance-scale text-4xl text-yellow-400 mb-2"></i>
                                            <p class="text-sm text-gray-400">Giustizia</p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <form method="POST" action="{{ route('sorting-hat.assign') }}">
                            @csrf
                            <input type="hidden" name="house" :value="sortedHouse">

                            <button type="submit"
                                    class="group inline-flex items-center px-8 py-4 bg-gradient-to-r text-gray-900 font-bold text-xl rounded-full shadow-2xl transform hover:scale-105 transition-all duration-300"
                                    :class="{
                                        'from-red-600 to-red-800 hover:from-red-500 hover:to-red-700': sortedHouse === 'Grifondoro',
                                        'from-green-600 to-green-800 hover:from-green-500 hover:to-green-700': sortedHouse === 'Serpeverde',
                                        'from-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700': sortedHouse === 'Corvonero',
                                        'from-yellow-600 to-yellow-800 hover:from-yellow-500 hover:to-yellow-700': sortedHouse === 'Tassorosso'
                                    }">
                                <i class="fas fa-door-open mr-3 text-2xl"></i>
                                Entra a Hogwarts
                                <i class="fas fa-sparkles ml-3 text-2xl group-hover:rotate-12 transition-transform"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
    function sortingCeremony() {
        return {
            currentStep: 'welcome',
            currentQuestion: 0,
            sortedHouse: '',
            houseScores: {
                'Grifondoro': 0,
                'Serpeverde': 0,
                'Corvonero': 0,
                'Tassorosso': 0
            },
            questions: [
                {
                    question: "Quale qualità apprezzi di più in te stesso?",
                    answers: [
                        { text: "Il mio coraggio e la mia audacia", house: 'Grifondoro' },
                        { text: "La mia astuzia e ambizione", house: 'Serpeverde' },
                        { text: "La mia intelligenza e saggezza", house: 'Corvonero' },
                        { text: "La mia lealtà e dedizione", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Come affronteresti un Troll nella scuola?",
                    answers: [
                        { text: "Lo affronto direttamente per proteggere gli altri", house: 'Grifondoro' },
                        { text: "Elaboro un piano astuto per ingannarlo", house: 'Serpeverde' },
                        { text: "Studio i suoi punti deboli e agisco di conseguenza", house: 'Corvonero' },
                        { text: "Lavoro con gli altri per sconfiggerlo insieme", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Quale sarebbe il tuo patronus ideale?",
                    answers: [
                        { text: "Un leone maestoso", house: 'Grifondoro' },
                        { text: "Un serpente elegante", house: 'Serpeverde' },
                        { text: "Un'aquila saggia", house: 'Corvonero' },
                        { text: "Un tasso leale", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Cosa faresti se trovassi la Pietra Filosofale?",
                    answers: [
                        { text: "La proteggerei con la mia vita", house: 'Grifondoro' },
                        { text: "La userei per ottenere potere e immortalità", house: 'Serpeverde' },
                        { text: "La studierei per comprenderne i segreti", house: 'Corvonero' },
                        { text: "La condividerei per il bene comune", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Quale materia ti affascina di più?",
                    answers: [
                        { text: "Difesa Contro le Arti Oscure", house: 'Grifondoro' },
                        { text: "Pozioni", house: 'Serpeverde' },
                        { text: "Incantesimi", house: 'Corvonero' },
                        { text: "Erbologia", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Come passeresti un giorno libero a Hogwarts?",
                    answers: [
                        { text: "Esplorando passaggi segreti e vivendo avventure", house: 'Grifondoro' },
                        { text: "Stringendo alleanze e pianificando il futuro", house: 'Serpeverde' },
                        { text: "Leggendo nella biblioteca e imparando cose nuove", house: 'Corvonero' },
                        { text: "Aiutando gli amici e passando tempo insieme", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Quale sarebbe il tuo ruolo ideale nel mondo magico?",
                    answers: [
                        { text: "Auror che combatte le forze oscure", house: 'Grifondoro' },
                        { text: "Ministro della Magia", house: 'Serpeverde' },
                        { text: "Professore o ricercatore", house: 'Corvonero' },
                        { text: "Guaritore o magicologo", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Cosa rappresenta per te l'amicizia?",
                    answers: [
                        { text: "Essere sempre pronti a difendere i propri amici", house: 'Grifondoro' },
                        { text: "Avere alleati fidati per raggiungere obiettivi comuni", house: 'Serpeverde' },
                        { text: "Condividere conoscenze e stimolarsi a vicenda", house: 'Corvonero' },
                        { text: "Essere sempre presenti nei momenti di bisogno", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Quale delle seguenti paure ti spaventa di più?",
                    answers: [
                        { text: "Essere considerato un codardo", house: 'Grifondoro' },
                        { text: "Fallire nei miei obiettivi", house: 'Serpeverde' },
                        { text: "Rimanere nell'ignoranza", house: 'Corvonero' },
                        { text: "Deludere le persone che amo", house: 'Tassorosso' }
                    ]
                },
                {
                    question: "Ultima domanda: Perché vuoi essere un mago?",
                    answers: [
                        { text: "Per vivere grandi avventure", house: 'Grifondoro' },
                        { text: "Per ottenere potere e influenza", house: 'Serpeverde' },
                        { text: "Per scoprire i segreti della magia", house: 'Corvonero' },
                        { text: "Per aiutare gli altri e rendere il mondo migliore", house: 'Tassorosso' }
                    ]
                }
            ],

            startSorting() {
                this.currentStep = 'quiz';
            },

            selectAnswer(answer) {
                this.houseScores[answer.house]++;

                if (this.currentQuestion < this.questions.length - 1) {
                    this.currentQuestion++;
                } else {
                    this.determineHouse();
                }
            },

            determineHouse() {
                let maxScore = 0;
                let selectedHouse = '';

                for (let house in this.houseScores) {
                    if (this.houseScores[house] > maxScore) {
                        maxScore = this.houseScores[house];
                        selectedHouse = house;
                    }
                }

                // Se c'è parità, sceglie casualmente tra le case con punteggio massimo
                let topHouses = Object.keys(this.houseScores).filter(house => this.houseScores[house] === maxScore);
                if (topHouses.length > 1) {
                    selectedHouse = topHouses[Math.floor(Math.random() * topHouses.length)];
                }

                this.sortedHouse = selectedHouse;
                this.currentStep = 'result';
            }
        }
    }
    </script>
</body>
</html>
