<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-12 leader">
                <div class="card">
                    <div class="auth">
                        <div class="auth-img">
                            <img src="/assets/house-stark.jpg">
                            <span>#3</span>
                        </div>
                        <div class="auth-info">
                            <div class="name">HOUSE STARK</div>
                            <div class="challenge">CHALLANGE POINTS</div>
                            <div class="points">28,456</div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4 d-md-none d-sm-block main">
                    <div class="card-header">SHIFT CHALLENGE</div>
                    <div class="card-banner">
                        <img src="/assets/activity-header.jpg">
                    </div>
                    <div class="card-body">
                        <div class="date">Saturday, Dec. 05</div>
                        <div class="header">TRACK YOUR STEPS</div>
                        <div class="mt-2">Using your movement-tracking device, keep track of the number of steps you take each day.</div>
                        <form class="mt-3">
                            <div class="strong">Enter your step count for this day.</div>
                            <div class="form-group mt-1">
                                <label for="steps">Enter your answer</label>
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="steps"
                                        :required="true">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">steps</span>
                                    </div>
                                </div>
                                <span class="error" v-if="error">{{error}}</span>
                            </div>
                            <button type="button" class="btn btn-primary mt-4" @click="saveSteps">SAVE ANSWER</button>
                        </form>
                    </div>
                </div>
                <div class="card mt-4 pb-4">
                    <div class="overall-rank">
                        <div class="auth-img">
                            <img src="/assets/trophy.svg">
                        </div>
                        <div class="auth-info">
                            <div>OVERALL</div>
                            <div>RANKING</div>
                        </div>
                    </div>
                    <ul class="ranking">
                        <li v-for="(leader, index) in leaderboard" :key="index" :class="{'selected' : leader.name == 'House Stark'}">
                            <img :src="`/assets/${leader.image}`" v-if="leader.image">
                            <img :src="`https://ui-avatars.com/api/?background=2C2E33&color=fff&name=${leader.name}`" v-else>
                            <div>{{index+1}}. {{leader.name}}</div>
                            <div class="points">{{leader.points}}pts</div>
                        </li>
                    </ul>
                </div>
                <div class="card mt-4 p-3 about">
                    <div class="heading">
                        <img src="/assets/question.svg">
                        ABOUT
                    </div>
                    <div class="date">
                        Closed On <span>Wednesday, 23 Dec. 2021, 5:00AM</span>
                    </div>
                    <p class="mt-3">
                        'SHIFT' was designed to show you how a seemingly small change, like adding more steps into your day with your team, can have a huge impact on you and those with whom you participated.
                    </p>
                </div>
            </div>
            <div class="col-md-8 d-sm-none d-md-block">
                <div class="card main">
                    <div class="card-header">SHIFT CHALLENGE</div>
                    <div class="card-banner">
                        <img src="/assets/activity-header.jpg">
                    </div>
                    <div class="card-body">
                        <div class="date">Saturday, Dec. 05</div>
                        <div class="header">TRACK YOUR STEPS</div>
                        <div class="mt-2">Using your movement-tracking device, keep track of the number of steps you take each day.</div>
                        <form class="mt-3">
                            <div class="strong">Enter your step count for this day.</div>
                            <div class="form-group mt-1">
                                <label for="steps">Enter your answer</label>
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="steps"
                                        :required="true">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">steps</span>
                                    </div>
                                </div>
                                <span class="error" v-if="error">{{error}}</span>
                            </div>
                            <button type="button" class="btn btn-primary mt-4" @click="saveSteps">SAVE ANSWER</button>
                        </form>
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
                leaderboard: [],
                steps: null,
                error: null,
            }
        },

        methods: {
            saveSteps() {
                this.validateSteps();
                if(!this.error){
                    let payload = {
                        'steps': this.steps
                    };

                    axios.post(`/save`, payload).then((response) => {
                        this.leaderboard = response.data;
                        this.steps = null;
                    })
                    .catch((error) => {
                        if(error.response.status == 422){
                            this.error = error.response.data.errors.steps[0];
                        } else {
                            this.error = 'Something went wrong';
                        }
                    });
                }
            },

            validateSteps() {
                this.error = null;
                if(!this.steps){
                    this.error = 'The steps is required';
                } else if(isNaN(this.steps)){
                    this.error = 'The steps must be a number';
                } else if(this.steps > 50000){
                    this.error = 'The steps must be less than 50000';
                } 
            }
        },

        mounted() {
            axios.get(`/get-leaderboard`).then((response) => {
                this.leaderboard = response.data;
            });
        },
    }
</script>