@extends('front.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 py-12" x-data="spellbook()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-magic font-bold text-white mb-4">
                <i class="fas fa-book-spells mr-3"></i>
                Grimorio degli Incantesimi
            </h1>
            <p class="text-xl text-purple-200">
                Studia e padroneggia gli incantesimi del mondo magico
            </p>
        </div>

        <!-- Stats Bar -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-wand-magic text-3xl text-purple-400 mr-4"></i>
                    <div>
                        <p class="text-sm text-purple-200">Incantesimi Conosciuti</p>
                        <p class="text-2xl font-bold text-white" x-text="knownSpells.length"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-star text-3xl text-yellow-400 mr-4"></i>
                    <div>
                        <p class="text-sm text-purple-200">Livello Magico</p>
                        <p class="text-2xl font-bold text-white">{{ Auth::user()->level ?? 1 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-bolt text-3xl text-blue-400 mr-4"></i>
                    <div>
                        <p class="text-sm text-purple-200">Mana Attuale</p>
                        <p class="text-2xl font-bold text-white" x-text="currentMana + ' / ' + maxMana"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-fire text-3xl text-red-400 mr-4"></i>
                    <div>
                        <p class="text-sm text-purple-200">Incantesimi Lanciati</p>
                        <p class="text-2xl font-bold text-white" x-text="totalSpellsCast"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-8">
            <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-2">
                <div class="flex flex-wrap gap-2">
                    <button @click="currentFilter = 'all'"
                            :class="currentFilter === 'all' ? 'bg-white text-indigo-900' : 'bg-transparent text-white hover:bg-white hover:bg-opacity-20'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-th mr-2"></i>Tutti
                    </button>
                    <button @click="currentFilter = 'attack'"
                            :class="currentFilter === 'attack' ? 'bg-red-600 text-white' : 'bg-transparent text-white hover:bg-red-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-fire-alt mr-2"></i>Attacco
                    </button>
                    <button @click="currentFilter = 'defense'"
                            :class="currentFilter === 'defense' ? 'bg-blue-600 text-white' : 'bg-transparent text-white hover:bg-blue-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-shield-alt mr-2"></i>Difesa
                    </button>
                    <button @click="currentFilter = 'healing'"
                            :class="currentFilter === 'healing' ? 'bg-green-600 text-white' : 'bg-transparent text-white hover:bg-green-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-heart mr-2"></i>Cura
                    </button>
                    <button @click="currentFilter = 'utility'"
                            :class="currentFilter === 'utility' ? 'bg-yellow-600 text-white' : 'bg-transparent text-white hover:bg-yellow-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-tools mr-2"></i>Utilità
                    </button>
                    <button @click="currentFilter = 'charm'"
                            :class="currentFilter === 'charm' ? 'bg-purple-600 text-white' : 'bg-transparent text-white hover:bg-purple-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-sparkles mr-2"></i>Incantesimi
                    </button>
                    <button @click="currentFilter = 'curse'"
                            :class="currentFilter === 'curse' ? 'bg-gray-900 text-white' : 'bg-transparent text-white hover:bg-gray-900 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-skull mr-2"></i>Maledizioni
                    </button>
                </div>
            </div>
        </div>

        <!-- Spells Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="spell in filteredSpells" :key="spell.id">
                <div @click="selectSpell(spell)"
                     :class="{
                        'border-gray-400': spell.rarity === 'common',
                        'border-green-500': spell.rarity === 'uncommon',
                        'border-blue-500': spell.rarity === 'rare',
                        'border-purple-500': spell.rarity === 'epic',
                        'border-yellow-500': spell.rarity === 'legendary'
                     }"
                     class="bg-white bg-opacity-10 backdrop-blur rounded-lg overflow-hidden border-l-4 hover:bg-opacity-20 transition-all duration-200 cursor-pointer transform hover:scale-105">

                    <!-- Header -->
                    <div :class="{
                        'bg-red-600': spell.type === 'attack',
                        'bg-blue-600': spell.type === 'defense',
                        'bg-green-600': spell.type === 'healing',
                        'bg-yellow-600': spell.type === 'utility',
                        'bg-purple-600': spell.type === 'charm',
                        'bg-gray-900': spell.type === 'curse',
                        'bg-indigo-600': spell.type === 'transfiguration'
                    }" class="p-4 flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-magic font-bold text-white mb-1" x-text="spell.name"></h3>
                            <p class="text-sm text-white opacity-90 italic" x-text="spell.incantation"></p>
                        </div>
                        <i :class="'fas ' + spell.icon" class="text-3xl text-white opacity-50"></i>
                    </div>

                    <!-- Body -->
                    <div class="p-4">
                        <p class="text-sm text-gray-200 mb-4 line-clamp-3" x-text="spell.description"></p>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <template x-if="spell.power > 0">
                                <div class="bg-red-900 bg-opacity-30 rounded p-2 text-center">
                                    <p class="text-xs text-gray-300">Potenza</p>
                                    <p class="text-lg font-bold text-red-300" x-text="spell.power"></p>
                                </div>
                            </template>

                            <div class="bg-blue-900 bg-opacity-30 rounded p-2 text-center">
                                <p class="text-xs text-gray-300">Mana</p>
                                <p class="text-lg font-bold text-blue-300" x-text="spell.mana_cost"></p>
                            </div>

                            <div class="bg-purple-900 bg-opacity-30 rounded p-2 text-center">
                                <p class="text-xs text-gray-300">Livello Req.</p>
                                <p class="text-lg font-bold text-purple-300" x-text="spell.required_level"></p>
                            </div>

                            <template x-if="spell.cooldown > 0">
                                <div class="bg-yellow-900 bg-opacity-30 rounded p-2 text-center">
                                    <p class="text-xs text-gray-300">Cooldown</p>
                                    <p class="text-lg font-bold text-yellow-300" x-text="spell.cooldown + 's'"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span :class="{
                                'bg-gray-700': spell.rarity === 'common',
                                'bg-green-700': spell.rarity === 'uncommon',
                                'bg-blue-700': spell.rarity === 'rare',
                                'bg-purple-700': spell.rarity === 'epic',
                                'bg-yellow-700': spell.rarity === 'legendary'
                            }" class="px-2 py-1 rounded text-xs font-bold text-white uppercase" x-text="spell.rarity"></span>

                            <template x-if="spell.is_forbidden">
                                <span class="px-2 py-1 rounded text-xs font-bold bg-red-900 text-white">
                                    <i class="fas fa-ban mr-1"></i>PROIBITO
                                </span>
                            </template>

                            <template x-if="isSpellKnown(spell.id)">
                                <span class="px-2 py-1 rounded text-xs font-bold bg-green-600 text-white">
                                    <i class="fas fa-check mr-1"></i>Conosciuto
                                </span>
                            </template>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <template x-if="canLearnSpell(spell)">
                                <button @click.stop="learnSpell(spell)"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition-colors">
                                    <i class="fas fa-book mr-1"></i>
                                    Impara
                                </button>
                            </template>

                            <template x-if="canCastSpell(spell)">
                                <button @click.stop="castSpell(spell)"
                                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded transition-colors">
                                    <i class="fas fa-wand-magic mr-1"></i>
                                    Lancia
                                </button>
                            </template>

                            <template x-if="!canLearnSpell(spell) && !canCastSpell(spell)">
                                <div class="flex-1 bg-gray-700 text-gray-400 font-medium py-2 px-4 rounded text-center text-sm">
                                    <i class="fas fa-lock mr-1"></i>
                                    Livello <span x-text="spell.required_level"></span> richiesto
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>

    <!-- Spell Detail Modal -->
    <div x-show="selectedSpell !== null"
         @click.self="selectedSpell = null"
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">

        <div @click.stop
             class="relative bg-gradient-to-br from-indigo-900 to-purple-900 rounded-2xl shadow-2xl max-w-2xl w-full border-4"
             :class="{
                'border-gray-400': selectedSpell && selectedSpell.rarity === 'common',
                'border-green-500': selectedSpell && selectedSpell.rarity === 'uncommon',
                'border-blue-500': selectedSpell && selectedSpell.rarity === 'rare',
                'border-purple-500': selectedSpell && selectedSpell.rarity === 'epic',
                'border-yellow-500': selectedSpell && selectedSpell.rarity === 'legendary'
             }">

            <template x-if="selectedSpell">
                <div>
                    <!-- Close Button -->
                    <button @click="selectedSpell = null"
                            class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl z-10">
                        <i class="fas fa-times"></i>
                    </button>

                    <!-- Spell Display -->
                    <div class="p-8">
                        <div class="text-center mb-8">
                            <i :class="'fas ' + selectedSpell.icon" class="text-8xl text-white mb-4"></i>
                            <h2 class="text-4xl font-magic font-bold text-white mb-2" x-text="selectedSpell.name"></h2>
                            <p class="text-2xl text-purple-200 italic mb-4" x-text="'"' + selectedSpell.incantation + '"'"></p>
                            <p class="text-lg text-gray-300" x-text="selectedSpell.description"></p>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-300 mb-1">Potenza</p>
                                <p class="text-3xl font-bold text-red-400" x-text="selectedSpell.power || '-'"></p>
                            </div>
                            <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-300 mb-1">Costo Mana</p>
                                <p class="text-3xl font-bold text-blue-400" x-text="selectedSpell.mana_cost"></p>
                            </div>
                            <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-300 mb-1">Livello</p>
                                <p class="text-3xl font-bold text-purple-400" x-text="selectedSpell.required_level"></p>
                            </div>
                            <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-300 mb-1">Cooldown</p>
                                <p class="text-3xl font-bold text-yellow-400" x-text="selectedSpell.cooldown ? selectedSpell.cooldown + 's' : '-'"></p>
                            </div>
                        </div>

                        <template x-if="selectedSpell.is_forbidden">
                            <div class="bg-red-900 bg-opacity-30 border border-red-600 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-lg font-bold text-red-300">MALEDIZIONE SENZA PERDONO</p>
                                        <p class="text-sm text-red-200">L'uso di questo incantesimo è severamente proibito e punito con l'ergastolo ad Azkaban!</p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="flex gap-3">
                            <template x-if="canLearnSpell(selectedSpell)">
                                <button @click="learnSpell(selectedSpell)"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition-colors">
                                    <i class="fas fa-graduation-cap mr-2"></i>
                                    Impara Incantesimo
                                </button>
                            </template>

                            <template x-if="canCastSpell(selectedSpell)">
                                <button @click="castSpell(selectedSpell)"
                                        class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition-colors">
                                    <i class="fas fa-wand-sparkles mr-2"></i>
                                    Lancia Incantesimo
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function spellbook() {
    return {
        spells: @json($spells ?? []),
        knownSpells: @json($userSpells ?? []),
        currentFilter: 'all',
        selectedSpell: null,
        currentMana: {{ Auth::user()->mana ?? 100 }},
        maxMana: {{ Auth::user()->max_mana ?? 100 }},
        totalSpellsCast: {{ Auth::user()->total_spells_cast ?? 0 }},

        get filteredSpells() {
            if (this.currentFilter === 'all') {
                return this.spells;
            }
            return this.spells.filter(spell => spell.type === this.currentFilter);
        },

        selectSpell(spell) {
            this.selectedSpell = spell;
        },

        isSpellKnown(spellId) {
            return this.knownSpells.some(s => s.spell_id === spellId);
        },

        canLearnSpell(spell) {
            const userLevel = {{ Auth::user()->level ?? 1 }};
            return !this.isSpellKnown(spell.id) && userLevel >= spell.required_level;
        },

        canCastSpell(spell) {
            const userLevel = {{ Auth::user()->level ?? 1 }};
            return this.isSpellKnown(spell.id) &&
                   userLevel >= spell.required_level &&
                   this.currentMana >= spell.mana_cost;
        },

        learnSpell(spell) {
            if (confirm(`Vuoi imparare l'incantesimo ${spell.name}?`)) {
                // AJAX call to learn spell
                alert(`Hai imparato ${spell.name}!`);
                this.knownSpells.push({ spell_id: spell.id, proficiency: 1 });
                this.selectedSpell = null;
            }
        },

        castSpell(spell) {
            if (this.currentMana < spell.mana_cost) {
                alert('Mana insufficiente!');
                return;
            }

            if (spell.is_forbidden && !confirm('ATTENZIONE: Stai per lanciare una MALEDIZIONE SENZA PERDONO! Sei sicuro?')) {
                return;
            }

            // Animation and casting
            this.currentMana -= spell.mana_cost;
            this.totalSpellsCast++;

            alert(`${spell.incantation} - Incantesimo lanciato con successo!`);

            // AJAX call to register spell cast
            console.log('Casting spell:', spell.name);

            this.selectedSpell = null;
        }
    }
}
</script>
@endpush

@endsection
