@extends('front.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-red-900 to-gray-900 py-8" x-data="combatArena()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Arena Header -->
        <div class="text-center mb-8">
            <h1 class="text-5xl font-magic font-bold text-white mb-2">
                <i class="fas fa-dragon mr-3 text-red-500"></i>
                Arena di Combattimento
            </h1>
            <p class="text-xl text-red-200">
                <span x-show="!inCombat">Scegli il tuo avversario e preparati alla battaglia!</span>
                <span x-show="inCombat" x-cloak>Turno <span x-text="currentTurn"></span></span>
            </p>
        </div>

        <!-- Battle Arena -->
        <div x-show="inCombat" x-cloak class="mb-8">
            <div class="bg-black bg-opacity-50 rounded-2xl p-8 border-4 border-red-900">

                <!-- Combat Field -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                    <!-- Player Side -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-blue-900 to-indigo-900 rounded-xl p-6 border-4 border-blue-500 transform"
                             :class="{'animate-pulse': isPlayerTurn}">

                            <!-- Player Status -->
                            <div class="flex items-center mb-4">
                                <img src="{{ url('upload/user/'.Auth::user()->avatar()) }}"
                                     class="w-16 h-16 rounded-full border-4 border-blue-400 mr-4">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-magic font-bold text-white">{{ Auth::user()->username }}</h3>
                                    <p class="text-sm text-blue-300">Livello <span x-text="player.level"></span></p>
                                </div>
                            </div>

                            <!-- HP Bar -->
                            <div class="mb-3">
                                <div class="flex justify-between text-sm text-white mb-1">
                                    <span>Punti Vita</span>
                                    <span x-text="player.hp + ' / ' + player.maxHp"></span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-full transition-all duration-500 flex items-center justify-center text-white text-xs font-bold"
                                         :style="'width: ' + (player.hp / player.maxHp * 100) + '%'">
                                        <span x-show="player.hp > 0" x-text="Math.round(player.hp / player.maxHp * 100) + '%'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Mana Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-white mb-1">
                                    <span>Mana</span>
                                    <span x-text="player.mana + ' / ' + player.maxMana"></span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-full transition-all duration-500"
                                         :style="'width: ' + (player.mana / player.maxMana * 100) + '%'"></div>
                                </div>
                            </div>

                            <!-- Player Stats -->
                            <div class="grid grid-cols-3 gap-2">
                                <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                    <i class="fas fa-fist-raised text-red-400"></i>
                                    <p class="text-xs text-gray-300">ATK</p>
                                    <p class="text-lg font-bold text-white" x-text="player.attack"></p>
                                </div>
                                <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                    <i class="fas fa-shield-alt text-blue-400"></i>
                                    <p class="text-xs text-gray-300">DEF</p>
                                    <p class="text-lg font-bold text-white" x-text="player.defense"></p>
                                </div>
                                <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                    <i class="fas fa-tachometer-alt text-yellow-400"></i>
                                    <p class="text-xs text-gray-300">SPD</p>
                                    <p class="text-lg font-bold text-white" x-text="player.speed"></p>
                                </div>
                            </div>

                            <!-- Active Effects -->
                            <div x-show="player.effects.length > 0" class="mt-4">
                                <p class="text-xs text-gray-300 mb-2">Effetti Attivi:</p>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="effect in player.effects" :key="effect.name">
                                        <span class="px-2 py-1 bg-purple-600 rounded text-xs text-white">
                                            <span x-text="effect.name"></span> (<span x-text="effect.turns"></span>)
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- VS Badge -->
                        <div class="absolute top-1/2 -right-8 transform -translate-y-1/2 bg-yellow-500 text-gray-900 font-black text-2xl px-4 py-2 rounded-full border-4 border-yellow-700 shadow-2xl z-10 hidden md:block">
                            VS
                        </div>
                    </div>

                    <!-- Enemy Side -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-red-900 to-gray-900 rounded-xl p-6 border-4 border-red-500 transform"
                             :class="{'animate-pulse': !isPlayerTurn && enemy.hp > 0}">

                            <!-- Enemy Status -->
                            <div class="flex items-center mb-4">
                                <div class="w-16 h-16 rounded-full border-4 border-red-400 mr-4 flex items-center justify-center bg-black">
                                    <i class="fas fa-dragon text-3xl text-red-500"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-2xl font-magic font-bold text-white" x-text="enemy.name"></h3>
                                    <p class="text-sm text-red-300">Livello <span x-text="enemy.level"></span></p>
                                </div>
                            </div>

                            <!-- HP Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-white mb-1">
                                    <span>Punti Vita</span>
                                    <span x-text="enemy.hp + ' / ' + enemy.maxHp"></span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-red-500 to-red-600 h-full transition-all duration-500 flex items-center justify-center text-white text-xs font-bold"
                                         :style="'width: ' + (enemy.hp / enemy.maxHp * 100) + '%'">
                                        <span x-show="enemy.hp > 0" x-text="Math.round(enemy.hp / enemy.maxHp * 100) + '%'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Enemy Stats -->
                            <div class="grid grid-cols-3 gap-2">
                                <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                    <i class="fas fa-fist-raised text-red-400"></i>
                                    <p class="text-xs text-gray-300">ATK</p>
                                    <p class="text-lg font-bold text-white" x-text="enemy.attack"></p>
                                </div>
                                <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                    <i class="fas fa-shield-alt text-blue-400"></i>
                                    <p class="text-xs text-gray-300">DEF</p>
                                    <p class="text-lg font-bold text-white" x-text="enemy.defense"></p>
                                </div>
                                <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                    <i class="fas fa-tachometer-alt text-yellow-400"></i>
                                    <p class="text-xs text-gray-300">SPD</p>
                                    <p class="text-lg font-bold text-white" x-text="enemy.speed"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Combat Log -->
                <div class="bg-gray-900 bg-opacity-50 rounded-lg p-4 mb-6 max-h-40 overflow-y-auto">
                    <h4 class="text-sm font-bold text-gray-400 mb-2">Log di Combattimento</h4>
                    <div class="space-y-1">
                        <template x-for="(log, index) in combatLog.slice().reverse()" :key="index">
                            <p class="text-sm text-gray-300" x-html="log"></p>
                        </template>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div x-show="isPlayerTurn && !battleEnded" x-cloak>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button @click="playerAttack()"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-lg transition-colors transform hover:scale-105">
                            <i class="fas fa-sword text-2xl mb-2"></i>
                            <p class="text-sm">Attacco Base</p>
                        </button>

                        <button @click="showSpellMenu = true"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-lg transition-colors transform hover:scale-105">
                            <i class="fas fa-wand-magic text-2xl mb-2"></i>
                            <p class="text-sm">Incantesimi</p>
                        </button>

                        <button @click="playerDefend()"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg transition-colors transform hover:scale-105">
                            <i class="fas fa-shield-alt text-2xl mb-2"></i>
                            <p class="text-sm">Difesa</p>
                        </button>

                        <button @click="playerFlee()"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-lg transition-colors transform hover:scale-105">
                            <i class="fas fa-running text-2xl mb-2"></i>
                            <p class="text-sm">Fuga</p>
                        </button>
                    </div>
                </div>

                <!-- Battle Result -->
                <div x-show="battleEnded" x-cloak class="text-center">
                    <div x-show="battleResult === 'victory'"
                         class="bg-green-600 bg-opacity-20 border-2 border-green-500 rounded-lg p-8">
                        <i class="fas fa-trophy text-6xl text-yellow-400 mb-4"></i>
                        <h3 class="text-3xl font-magic font-bold text-white mb-2">VITTORIA!</h3>
                        <p class="text-green-300 mb-4">Hai sconfitto <span x-text="enemy.name"></span>!</p>
                        <div class="flex justify-center gap-4 mb-4">
                            <div class="bg-black bg-opacity-30 rounded px-4 py-2">
                                <p class="text-sm text-gray-300">XP Guadagnato</p>
                                <p class="text-2xl font-bold text-blue-400" x-text="rewards.exp"></p>
                            </div>
                            <div class="bg-black bg-opacity-30 rounded px-4 py-2">
                                <p class="text-sm text-gray-300">Galeoni</p>
                                <p class="text-2xl font-bold text-yellow-400" x-text="rewards.money"></p>
                            </div>
                        </div>
                        <button @click="endBattle()"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg">
                            Continua
                        </button>
                    </div>

                    <div x-show="battleResult === 'defeat'"
                         class="bg-red-600 bg-opacity-20 border-2 border-red-500 rounded-lg p-8">
                        <i class="fas fa-skull text-6xl text-red-400 mb-4"></i>
                        <h3 class="text-3xl font-magic font-bold text-white mb-2">SCONFITTA</h3>
                        <p class="text-red-300 mb-4">Sei stato sconfitto da <span x-text="enemy.name"></span>...</p>
                        <button @click="endBattle()"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg">
                            Riprova
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Creature Selection -->
        <div x-show="!inCombat" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="creature in creatures" :key="creature.id">
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg overflow-hidden border-2 border-gray-700 hover:border-red-500 transition-all duration-200 transform hover:scale-105 cursor-pointer"
                     @click="startBattle(creature)">

                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 rounded-full bg-red-900 flex items-center justify-center mr-4">
                                <i class="fas fa-dragon text-3xl text-red-500"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-magic font-bold text-white" x-text="creature.name"></h3>
                                <p class="text-sm text-gray-400">Livello <span x-text="creature.level"></span></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                <p class="text-xs text-gray-400">HP</p>
                                <p class="text-lg font-bold text-green-400" x-text="creature.hp"></p>
                            </div>
                            <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                <p class="text-xs text-gray-400">ATK</p>
                                <p class="text-lg font-bold text-red-400" x-text="creature.dmg"></p>
                            </div>
                            <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                <p class="text-xs text-gray-400">DEF</p>
                                <p class="text-lg font-bold text-blue-400" x-text="creature.defense || 5"></p>
                            </div>
                        </div>

                        <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            <i class="fas fa-swords mr-2"></i>
                            Sfida!
                        </button>
                    </div>
                </div>
            </template>
        </div>

    </div>
</div>

@push('scripts')
<script>
function combatArena() {
    return {
        creatures: @json($creatures ?? []),
        inCombat: false,
        isPlayerTurn: true,
        currentTurn: 1,
        battleEnded: false,
        battleResult: null,
        combatLog: [],
        showSpellMenu: false,

        player: {
            hp: {{ Auth::user()->hp ?? 100 }},
            maxHp: {{ Auth::user()->max_hp ?? 100 }},
            mana: {{ Auth::user()->mana ?? 100 }},
            maxMana: {{ Auth::user()->max_mana ?? 100 }},
            attack: {{ Auth::user()->attack ?? 10 }},
            defense: {{ Auth::user()->defense ?? 5 }},
            speed: {{ Auth::user()->speed ?? 10 }},
            level: {{ Auth::user()->level ?? 1 }},
            effects: []
        },

        enemy: {
            name: '',
            hp: 0,
            maxHp: 0,
            attack: 0,
            defense: 0,
            speed: 0,
            level: 0
        },

        rewards: {
            exp: 0,
            money: 0
        },

        startBattle(creature) {
            this.inCombat = true;
            this.battleEnded = false;
            this.battleResult = null;
            this.combatLog = [];
            this.currentTurn = 1;

            this.enemy = {
                id: creature.id,
                name: creature.name,
                hp: creature.hp,
                maxHp: creature.hp,
                attack: creature.dmg,
                defense: creature.defense || 5,
                speed: creature.speed || 8,
                level: creature.level
            };

            this.addLog(`<strong class="text-red-400">Combattimento iniziato contro ${creature.name}!</strong>`);

            // Determina chi attacca per primo
            if (this.enemy.speed > this.player.speed) {
                this.isPlayerTurn = false;
                this.addLog('Il nemico è più veloce e attacca per primo!');
                setTimeout(() => this.enemyTurn(), 1000);
            } else {
                this.addLog('Tocca a te! Scegli la tua azione.');
            }
        },

        playerAttack() {
            const damage = Math.max(1, this.player.attack - this.enemy.defense + this.randomModifier());
            this.enemy.hp = Math.max(0, this.enemy.hp - damage);

            this.addLog(`Hai attaccato ${this.enemy.name} per <span class="text-red-400">${damage} danni</span>!`);

            this.checkBattleEnd();
            if (!this.battleEnded) {
                this.endPlayerTurn();
            }
        },

        playerDefend() {
            this.player.effects.push({ name: 'Difesa', defense: 5, turns: 1 });
            this.addLog('Ti sei messo in guardia! Difesa aumentata per questo turno.');
            this.endPlayerTurn();
        },

        playerFlee() {
            const fleeChance = Math.random();
            if (fleeChance > 0.5) {
                this.addLog('<span class="text-yellow-400">Sei fuggito dal combattimento!</span>');
                this.endBattle();
            } else {
                this.addLog('<span class="text-red-400">Tentativo di fuga fallito!</span>');
                this.endPlayerTurn();
            }
        },

        endPlayerTurn() {
            this.isPlayerTurn = false;
            this.currentTurn++;
            setTimeout(() => this.enemyTurn(), 1500);
        },

        enemyTurn() {
            if (this.battleEnded) return;

            const damage = Math.max(1, this.enemy.attack - this.player.defense + this.randomModifier());
            this.player.hp = Math.max(0, this.player.hp - damage);

            this.addLog(`${this.enemy.name} ti attacca per <span class="text-red-400">${damage} danni</span>!`);

            // Remove expired effects
            this.player.effects = this.player.effects.filter(e => --e.turns > 0);

            this.checkBattleEnd();
            if (!this.battleEnded) {
                this.isPlayerTurn = true;
            }
        },

        checkBattleEnd() {
            if (this.enemy.hp <= 0) {
                this.battleEnded = true;
                this.battleResult = 'victory';
                this.calculateRewards();
                this.addLog('<strong class="text-green-400">VITTORIA!</strong>');
            } else if (this.player.hp <= 0) {
                this.battleEnded = true;
                this.battleResult = 'defeat';
                this.addLog('<strong class="text-red-400">SEI STATO SCONFITTO!</strong>');
            }
        },

        calculateRewards() {
            this.rewards.exp = Math.round(this.enemy.level * 15 * (1 + Math.random() * 0.3));
            this.rewards.money = Math.round(this.enemy.level * 10 * (1 + Math.random() * 0.5));
        },

        endBattle() {
            this.inCombat = false;
            // AJAX call to save battle results
            if (this.battleResult === 'victory') {
                alert(`Hai guadagnato ${this.rewards.exp} XP e ${this.rewards.money} Galeoni!`);
            }
        },

        randomModifier() {
            return Math.floor(Math.random() * 5) - 2; // -2 to +2
        },

        addLog(message) {
            this.combatLog.push(message);
            if (this.combatLog.length > 10) {
                this.combatLog.shift();
            }
        }
    }
}
</script>
@endpush

@endsection
