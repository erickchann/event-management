<template>
    <div>
        <div class="container">

            <div class="alert" v-if="alert">
                {{ alert }}
            </div>

            <main v-if="!showDetail">
                <header>
                    <h1>{{ event.name }}</h1>

                    <router-link :to="`/organizer/${organizerSlug}/event/${eventSlug}/registration`" type="button" id="register">Register for this event</router-link>
                </header>


                <div class="table">
                    <div class="timeline-container">
                        <div class="timeline">
                            <div v-for="(n, i) in hour.max - hour.min" :key="n" :style="`width: ${getHourWidth()}%`">{{ hour.min + i }}</div>
                        </div>
                    </div>

                    <tr v-for="channel in event.channels" :key="channel.id">
                        <td class="channel"><h3>{{ channel.name }}</h3></td>
                        <td class="room">
                            <div v-for="room in channel.rooms" :key="room.id">
                                {{ room.name }}
                            </div>
                        </td>
                        <td class="rows">
                            <div v-for="room in channel.rooms" :key="room.id" class="row">
                                <a @click.prevent="showDetail = session" href="" v-for="session in room.sessions" :key="session.id" class="session" :style="`left: ${getLeftOffset(session.start)}%; width: ${getSessionWidth(getMinute(session))}%`">
                                    {{ session.title }}
                                </a>
                            </div>
                        </td>
                    </tr>
                </div>
            </main>

            <main v-if="showDetail">
                <header>
                    <h1>{{ showDetail.title }}</h1>

                    <button type="button" class="login-btn" @click="showDetail = null">Close</button>
                </header>

                <h4>{{ showDetail.description }}</h4>

                <table>
                    <tr>
                        <td><h4>Speaker:</h4></td>
                        <td></td>
                        <td>{{ showDetail.speaker }}</td>
                    </tr>
                    <tr>
                        <td><h4>Start:</h4></td>
                        <td></td>
                        <td>{{ showDetail.start }}</td>
                    </tr>
                    <tr>
                        <td><h4>End:</h4></td>
                        <td></td>
                        <td>{{ showDetail.end }}</td>
                    </tr>
                    <tr>
                        <td><h4>Cost:</h4></td>
                        <td></td>
                        <td>{{ showDetail.cost || 0}}.-</td>
                    </tr>
                </table>
            </main>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            alert: localStorage.getItem('alert'),
            showDetail: '',
            organizerSlug: this.$route.params.organizerSlug, 
            eventSlug: this.$route.params.eventSlug,
            event: '',

            hour: {
                min: 0,
                max: 0
            }
        }
    },
    methods: {
        getEvent() {
            axios.get(`organizers/${this.organizerSlug}/events/${this.eventSlug}`)
                .then(res => {
                    console.log(res.data);

                    this.event = res.data;
                    this.getHourRange();
                })
                .catch(err => {
                    alert(err.response.data.message);
                });
        },
        getHourRange() {
            this.event.channels.forEach(channel => {
                channel.rooms.forEach(room => {
                    room.sessions.forEach(session => {
                        let start = new Date(session.start);
                        let end = new Date(session.end);

                        if (!this.hour.min) this.hour.min = start.getHours();

                        if (this.hour.min > start.getHours()) this.hour.min = start.getHours();
                        if (this.hour.max < end.getHours()) this.hour.max = end.getHours();
                    });
                });
            });
        },
        getHourWidth() {
            return 100 / (this.hour.max - this.hour.min);
        },
        getMinute(session) {
            let startHour = new Date(session.start).getHours();
            let endHour = new Date(session.end).getHours();

            let startMinute = new Date(session.start).getMinutes();
            let endMinute = new Date(session.end).getMinutes();

            let res = ((endHour * 60) + endMinute) - ((startHour * 60) + startMinute);

            return res;
        },
        getLeftOffset(time) {
            let min = this.hour.min;
            let startMinute = new Date(time).getMinutes();
            let startHour = new Date(time).getHours();

            let calculate = ((startHour * 60) + startMinute) - (min * 60);

            return this.getSessionWidth(calculate);
        },
        getSessionWidth(minute) {
            let range = this.hour.max - this.hour.min;

            return (minute / (range * 60)) * 100;
        }
    },
    created() {
        this.getEvent();
    },
    mounted() {
        localStorage.removeItem('alert');
    },
}
</script>

<style scoped>

main {
    overflow-x: scroll;
}

header {
    margin-bottom: 2rem;
}

.timeline-container {
    width: 100%;
    display: flex;
    justify-content: flex-end;
    border-bottom: 1px solid black;
}

.timeline {
    display: flex;
    width: 70%;
}

.alert {
    padding: 1rem 2rem;
    background-color: rgb(171, 255, 163);
    color: green;
    border-radius: 5px;
    margin-bottom: 3rem;
}

tr {
    display: flex;
    align-items: center;
    border-bottom: 1px solid black;
}

.table {
    width: 100%;
}

.channel {
    width: 20%;
}

.room {
    width: 10%;
}

.room div {
    margin: 10px 0;
    height: 40px;
    line-height: 40px;
}

.rows {
    width: 70%;
    height: fit-content;
}

.row {
    height: 40px;
    width: 100%;
    margin: 10px 0;
    position: relative;
}

.session {
    padding: 0 10px;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    position: absolute;
    display: inline-block;
    text-decoration: none;
    color: black;
    border: 1px solid black;
    border-radius: var(--border1);
    margin: 10px 0;
}

</style>