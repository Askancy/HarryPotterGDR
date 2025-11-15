@extends('front.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-900 via-orange-900 to-red-900 py-12" x-data="achievementTracker()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-magic font-bold text-white mb-4">
                <i class="fas fa-trophy mr-3 text-yellow-400"></i>
                Riconoscimenti e Traguardi
            </h1>
            <p class="text-xl text-yellow-200">
                Completa sfide epiche e guadagna ricompense esclusive
            </p>
        </div>

        <!-- Progress Overview -->
        <div class="bg-white bg-opacity-10 backdrop-blur rounded-2xl p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <i class="fas fa-award text-6xl text-yellow-400 mb-3"></i>
                    <p class="text-2xl font-bold text-white" x-text="completedAchievements.length"></p>
                    <p class="text-sm text-gray-300">Achievement Sbloccati</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-star text-6xl text-blue-400 mb-3"></i>
                    <p class="text-2xl font-bold text-white" x-text="totalPoints"></p>
                    <p class="text-sm text-gray-300">Punti Totali</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-percentage text-6xl text-green-400 mb-3"></i>
                    <p class="text-2xl font-bold text-white" x-text="completionPercentage + '%'"></p>
                    <p class="text-sm text-gray-300">Completamento</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-fire text-6xl text-red-400 mb-3"></i>
                    <p class="text-2xl font-bold text-white" x-text="currentStreak"></p>
                    <p class="text-sm text-gray-300">Serie di Giorni</p>
                </div>
            </div>
        </div>

        <!-- Category Filters -->
        <div class="mb-8">
            <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-2">
                <div class="flex flex-wrap gap-2">
                    <button @click="currentCategory = 'all'"
                            :class="currentCategory === 'all' ? 'bg-white text-gray-900' : 'bg-transparent text-white hover:bg-white hover:bg-opacity-20'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-th mr-2"></i>Tutti
                    </button>
                    <button @click="currentCategory = 'combat'"
                            :class="currentCategory === 'combat' ? 'bg-red-600 text-white' : 'bg-transparent text-white hover:bg-red-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-sword mr-2"></i>Combattimento
                    </button>
                    <button @click="currentCategory = 'exploration'"
                            :class="currentCategory === 'exploration' ? 'bg-blue-600 text-white' : 'bg-transparent text-white hover:bg-blue-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-map mr-2"></i>Esplorazione
                    </button>
                    <button @click="currentCategory = 'social'"
                            :class="currentCategory === 'social' ? 'bg-green-600 text-white' : 'bg-transparent text-white hover:bg-green-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-users mr-2"></i>Sociale
                    </button>
                    <button @click="currentCategory = 'collection'"
                            :class="currentCategory === 'collection' ? 'bg-yellow-600 text-white' : 'bg-transparent text-white hover:bg-yellow-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-box mr-2"></i>Collezione
                    </button>
                    <button @click="currentCategory = 'mastery'"
                            :class="currentCategory === 'mastery' ? 'bg-purple-600 text-white' : 'bg-transparent text-white hover:bg-purple-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-crown mr-2"></i>Maestria
                    </button>
                    <button @click="currentCategory = 'special'"
                            :class="currentCategory === 'special' ? 'bg-pink-600 text-white' : 'bg-transparent text-white hover:bg-pink-600 hover:bg-opacity-50'"
                            class="px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-star mr-2"></i>Speciali
                    </button>
                </div>
            </div>
        </div>

        <!-- Achievements Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="achievement in filteredAchievements" :key="achievement.id">
                <div @click="selectAchievement(achievement)"
                     :class="{
                        'border-gray-400 opacity-50': !isCompleted(achievement.id) && achievement.rarity === 'common',
                        'border-green-500 opacity-50': !isCompleted(achievement.id) && achievement.rarity === 'uncommon',
                        'border-blue-500 opacity-50': !isCompleted(achievement.id) && achievement.rarity === 'rare',
                        'border-purple-500 opacity-50': !isCompleted(achievement.id) && achievement.rarity === 'epic',
                        'border-yellow-500 opacity-50': !isCompleted(achievement.id) && achievement.rarity === 'legendary',
                        'border-gray-400': isCompleted(achievement.id) && achievement.rarity === 'common',
                        'border-green-500': isCompleted(achievement.id) && achievement.rarity === 'uncommon',
                        'border-blue-500': isCompleted(achievement.id) && achievement.rarity === 'rare',
                        'border-purple-500': isCompleted(achievement.id) && achievement.rarity === 'epic',
                        'border-yellow-500': isCompleted(achievement.id) && achievement.rarity === 'legendary'
                     }"
                     class="bg-white bg-opacity-10 backdrop-blur rounded-lg overflow-hidden border-l-4 hover:bg-opacity-20 transition-all duration-200 cursor-pointer transform hover:scale-105 relative">

                    <!-- Completed Badge -->
                    <template x-if="isCompleted(achievement.id)">
                        <div class="absolute top-2 right-2 bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold z-10">
                            <i class="fas fa-check mr-1"></i>COMPLETATO
                        </div>
                    </template>

                    <!-- Hidden Badge -->
                    <template x-if="achievement.is_hidden && !isCompleted(achievement.id)">
                        <div class="absolute inset-0 bg-black bg-opacity-80 flex items-center justify-center z-20 rounded">
                            <div class="text-center">
                                <i class="fas fa-lock text-6xl text-gray-400 mb-3"></i>
                                <p class="text-white font-bold">Achievement Segreto</p>
                                <p class="text-gray-400 text-sm">Continua a giocare per sbloccarlo!</p>
                            </div>
                        </div>
                    </template>

                    <div class="p-6">
                        <!-- Icon -->
                        <div class="text-center mb-4">
                            <i :class="'fas ' + achievement.icon + ' text-6xl'"
                               :class="{
                                   'text-gray-400': achievement.rarity === 'common',
                                   'text-green-400': achievement.rarity === 'uncommon',
                                   'text-blue-400': achievement.rarity === 'rare',
                                   'text-purple-400': achievement.rarity === 'epic',
                                   'text-yellow-400': achievement.rarity === 'legendary'
                               }"></i>
                        </div>

                        <!-- Name -->
                        <h3 class="text-xl font-magic font-bold text-white text-center mb-2" x-text="achievement.name"></h3>

                        <!-- Description -->
                        <p class="text-sm text-gray-300 text-center mb-4" x-text="achievement.description"></p>

                        <!-- Progress Bar -->
                        <template x-if="!isCompleted(achievement.id)">
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-400 mb-1">
                                    <span>Progresso</span>
                                    <span x-text="getProgress(achievement.id) + ' / ' + achievement.required_value"></span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-full transition-all duration-500"
                                         :style="'width: ' + (getProgress(achievement.id) / achievement.required_value * 100) + '%'"></div>
                                </div>
                            </div>
                        </template>

                        <!-- Rewards -->
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                <p class="text-xs text-gray-400">Punti</p>
                                <p class="text-sm font-bold text-yellow-400" x-text="achievement.points"></p>
                            </div>
                            <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                <p class="text-xs text-gray-400">XP</p>
                                <p class="text-sm font-bold text-blue-400" x-text="achievement.exp_reward"></p>
                            </div>
                            <div class="bg-black bg-opacity-30 rounded p-2 text-center">
                                <p class="text-xs text-gray-400">Galeoni</p>
                                <p class="text-sm font-bold text-yellow-400" x-text="achievement.money_reward"></p>
                            </div>
                        </div>

                        <!-- Rarity Badge -->
                        <div class="text-center">
                            <span :class="{
                                'bg-gray-700': achievement.rarity === 'common',
                                'bg-green-700': achievement.rarity === 'uncommon',
                                'bg-blue-700': achievement.rarity === 'rare',
                                'bg-purple-700': achievement.rarity === 'epic',
                                'bg-yellow-700': achievement.rarity === 'legendary'
                            }" class="px-3 py-1 rounded-full text-xs font-bold text-white uppercase" x-text="achievement.rarity"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>
</div>

@push('scripts')
<script>
function achievementTracker() {
    return {
        achievements: @json($achievements ?? []),
        userAchievements: @json($userAchievements ?? []),
        currentCategory: 'all',
        currentStreak: {{ Auth::user()->login_streak ?? 0 }},

        get filteredAchievements() {
            if (this.currentCategory === 'all') {
                return this.achievements;
            }
            return this.achievements.filter(a => a.category === this.currentCategory);
        },

        get completedAchievements() {
            return this.userAchievements.filter(ua => ua.completed);
        },

        get totalPoints() {
            return this.completedAchievements.reduce((sum, ua) => {
                const achievement = this.achievements.find(a => a.id === ua.achievement_id);
                return sum + (achievement ? achievement.points : 0);
            }, 0);
        },

        get completionPercentage() {
            if (this.achievements.length === 0) return 0;
            return Math.round((this.completedAchievements.length / this.achievements.length) * 100);
        },

        isCompleted(achievementId) {
            const userAch = this.userAchievements.find(ua => ua.achievement_id === achievementId);
            return userAch && userAch.completed;
        },

        getProgress(achievementId) {
            const userAch = this.userAchievements.find(ua => ua.achievement_id === achievementId);
            return userAch ? userAch.progress : 0;
        },

        selectAchievement(achievement) {
            // Could show a detailed modal
            console.log('Selected achievement:', achievement);
        }
    }
}
</script>
@endpush

@endsection
