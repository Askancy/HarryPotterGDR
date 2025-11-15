<div x-data="houseRankingWidget()" x-init="loadRanking()" class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <span class="text-2xl mr-2">🏆</span>
        Classifica Case
    </h3>

    <div class="space-y-3">
        <template x-for="(house, index) in houses" :key="house.id">
            <div class="relative overflow-hidden rounded-lg border-2"
                 :class="{
                    'border-yellow-400 shadow-lg': index === 0,
                    'border-gray-300': index !== 0
                 }">
                <!-- Background gradient -->
                <div class="absolute inset-0 opacity-10"
                     :class="{
                        'bg-gradient-to-r from-red-500 to-orange-500': house.id === 1,
                        'bg-gradient-to-r from-green-600 to-green-800': house.id === 2,
                        'bg-gradient-to-r from-blue-600 to-blue-800': house.id === 3,
                        'bg-gradient-to-r from-yellow-500 to-yellow-600': house.id === 4
                     }">
                </div>

                <!-- Content -->
                <div class="relative p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="text-2xl font-bold text-gray-400"
                             :class="{ 'text-yellow-500': index === 0 }">
                            #<span x-text="house.rank"></span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800" x-text="house.name"></div>
                            <div class="text-sm text-gray-600">
                                <span x-text="house.members"></span> membri
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold"
                             :class="{
                                'text-red-600': house.id === 1,
                                'text-green-700': house.id === 2,
                                'text-blue-700': house.id === 3,
                                'text-yellow-600': house.id === 4
                             }"
                             x-text="house.points">
                        </div>
                        <div class="text-xs text-gray-500">punti</div>
                    </div>
                </div>

                <!-- Winner crown -->
                <template x-if="index === 0">
                    <div class="absolute -top-2 -right-2 bg-yellow-400 text-white rounded-full p-2 shadow-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <!-- Progress bars showing relative standings -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="text-sm text-gray-600 mb-3 font-semibold">Distribuzione Punti</div>
        <template x-for="house in houses" :key="'bar-' + house.id">
            <div class="mb-2">
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span x-text="house.name"></span>
                    <span x-text="house.points + ' pts'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-500"
                         :class="{
                            'bg-gradient-to-r from-red-500 to-orange-500': house.id === 1,
                            'bg-gradient-to-r from-green-600 to-green-800': house.id === 2,
                            'bg-gradient-to-r from-blue-600 to-blue-800': house.id === 3,
                            'bg-gradient-to-r from-yellow-500 to-yellow-600': house.id === 4
                         }"
                         :style="'width: ' + (maxPoints > 0 ? (house.points / maxPoints * 100) : 0) + '%'">
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Last update -->
    <div class="mt-4 text-xs text-gray-500 text-center">
        Aggiornato <span x-text="lastUpdate"></span>
    </div>
</div>

<script>
function houseRankingWidget() {
    return {
        houses: [],
        maxPoints: 0,
        lastUpdate: 'ora',
        pollInterval: null,

        async loadRanking() {
            try {
                const response = await fetch('/api/house-points/ranking');
                const data = await response.json();

                this.houses = data.houses;
                this.maxPoints = Math.max(...this.houses.map(h => h.points));
                this.lastUpdate = 'pochi secondi fa';

                // Start polling every 30 seconds
                if (!this.pollInterval) {
                    this.pollInterval = setInterval(() => this.loadRanking(), 30000);
                }
            } catch (error) {
                console.error('Error loading house ranking:', error);
            }
        }
    }
}
</script>
