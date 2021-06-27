<template>
    <div v-cloak class="text-white mb-8">
        <div v-if="errorStr" class="bg-red-100 border-l-5 border-red-500 rounded text-red-500 p-4" role="alert">
            <p class="font-bold">Erro</p>
            <p>{{ errorStr }}</p>
        </div>

        <div v-if="gettingLocation" class="bg-yellow-100 border-l-5 border-yellow-500 rounded text-yellow-500 p-4" role="alert">
            <p class="font-bold">A avisar</p>
            <p>A obter sua localização...</p>
        </div>

        <!-- Conteudo do tempo -->
        <div class="weather-container font-sans w-128 max-w-lg rounded-lg overflow-hidden bg-gray-900 shadow-lg mt-4">
            <!-- Tempo atual -->
            <div class="current-weather flex items-center justify-between px-6 py-8">
                <div class="flex items-center">
                    <div class="">
                        <div class="text-6xl font-semibold">{{ currentTemperature.actual }}ºC</div>
                        <div>Feels like {{ currentTemperature.feels }}ºC</div>
                    </div>
                    <div class="mx-5">
                        <div class="font-semibold">{{ currentTemperature.summary }}</div>
                        <div>{{ currentTemperature.timezone }}</div>
                    </div>
                </div>
                <div>
                    <img style="width:72px" v-bind:src="currentTemperature.icon">
                </div>
            </div>

            <!-- Proximos dias do tempo -->
            <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">
                <div v-for="(day, index) in daily" :key="day.dt" class="flex items-center" :class="{ 'mt-8' : index > 0}" v-if="index < 5">
                    <div class="w-1/6 text-lg text-gray-200">{{ weekDay(day.dt) }}</div>
                    <div class="w-4/6 px-4 flex items-center">
                        <div>
                            <img :src="'http://openweathermap.org/img/w/' + day.weather[0]['icon'] + '.png'">
                        </div>
                        <div class="ml-4">{{ day.weather[0]['description'].charAt(0).toUpperCase() + day.weather[0]['description'].slice(1) }}</div>
                    </div>
                    <div class="w-1/6 text-right">
                        <div>{{ Math.round(day.temp.max) }}ºC</div>
                        <div>{{ Math.round(day.temp.min) }}ºC</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentTemperature: {
                timezone: '',
                actual: '',
                feels: '',
                summary: '',
                icon: '',
            },
            daily: [],
            location:null,
            gettingLocation: false,
            errorStr: null
        }
    },
    created(){
        if(!("geolocation" in navigator)) {
            this.errorStr = 'A geolocalização não está disponível.';
            return;
        }

        this.gettingLocation = true;

        navigator.geolocation.getCurrentPosition(pos => {
            this.gettingLocation = false;
            this.location = pos;

            fetch(`/api/weather?lat=${this.location.coords.latitude}&lon=${this.location.coords.longitude}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    this.currentTemperature.timezone = data.timezone;
                    this.currentTemperature.actual = Math.round(data.current.temp);
                    this.currentTemperature.feels = Math.round(data.current.feels_like);
                    this.currentTemperature.summary = data.current.weather[0]['description'].charAt(0).toUpperCase() + data.current.weather[0]['description'].slice(1);
                    this.currentTemperature.icon = "http://openweathermap.org/img/w/" + data.current.weather[0]['icon'] + ".png";
                    
                    this.daily = data.daily
                })
        }, err => {
            this.gettingLocation = false;
            this.errorStr = err.message;
        })
    },
    methods: {
        weekDay(timestamp) {
            const newDate = new Date(timestamp*1000)
            const days = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado']

            return days[newDate.getDay()];
        }
    }
}
</script>
