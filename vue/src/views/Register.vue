<template>
    <div>
        <div class="container">
            <main>
                <h1>{{ event.name }}</h1>

                <div class="tickets">
                    <div v-for="ticket in event.tickets" :key="ticket.id">
                        <div class="ticket" v-if="ticket.available">
                            <input type="radio" name="ticket" :value="ticket.id" v-model="formData.ticket_id" @change="ticketPrice($event, ticket.cost, true)">

                            <div class="info">
                                <h4>{{ ticket.name }}</h4>
                                <p>{{ ticket.description }}</p>
                            </div>
                        </div>

                        <div class="ticket disabled" v-if="!ticket.available">
                            <input type="radio" name="ticket" :value="ticket.id" v-model="formData.ticket_id" @change="ticketPrice($event, ticket.cost, false)">

                            <div class="info">
                                <h4>{{ ticket.name }}</h4>
                                <p>{{ ticket.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <h2>Select additional workshops you want to book:</h2>

                <div class="workshops">
                    <div v-for="channel in event.channels" :key="channel.id">
                        <div v-for="room in channel.rooms" :key="room.id">
                            <div v-for="session in room.sessions" :key="session.id">
                                <div class="workskop">
                                    <input type="checkbox" name="workshop" :id="session.id" :value="session.id" v-model="formData.session_ids" @change="additionalPrice($event, session.cost)">
                                    <label :for="session.id">{{ session.title }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="order">
                    <table>
                        <tr>
                            <td>Event Ticket</td>
                            <td></td>
                            <td>{{ ticket }}.-</td>
                        </tr>
                        <tr>
                            <td>Additional workshop</td>
                            <td></td>
                            <td>{{ additional }}.-</td>
                        </tr>
                        <tr>
                            <td><h5>Total</h5></td>
                            <td></td>
                            <td>{{ parseInt(ticket) + parseInt(additional) }}.-</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td v-if="!formData.ticket_id || !valid"><button type="button" class="login-btn disabled">Purchase</button></td>
                            <td v-if="formData.ticket_id && valid"><button type="button" class="login-btn" @click="purchase">Purchase</button></td>
                        </tr>
                    </table>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            loginData: JSON.parse(localStorage.getItem('login')),
            organizerSlug: this.$route.params.organizerSlug, 
            eventSlug: this.$route.params.eventSlug,
            event: '',
            ticket: 0,
            additional: 0,
            formData: {
                ticket_id: '',
                session_ids: []
            },
            valid: false
        }
    },
    methods: {
        getEvent() {
            axios.get(`organizers/${this.organizerSlug}/events/${this.eventSlug}`)
                .then(res => {
                    console.log(res.data);
                    this.event = res.data;
                })
                .catch(err => {
                    alert(err.response.data.message);
                });
        },
        ticketPrice(e, cost, valid) {
            if (valid) {
                this.valid = true;
            } else {
                this.valid = false;
            }

            if (e.target.checked) {
                this.ticket = parseInt(cost);
            }
        },
        additionalPrice(e, cost) {
            if (!cost) return;

            if (e.target.checked) {
                this.additional += parseInt(cost);
            } else {
                this.additional -= parseInt(cost);
            }
        },
        purchase() {
            if (!this.loginData) {
                alert('Not logged in');

                return;
            }

            axios.post(`organizers/${this.organizerSlug}/events/${this.eventSlug}/registration?token=${this.loginData.token}`, this.formData)
                .then(res => {
                    localStorage.setItem('alert', 'Registration successful');

                    this.$router.go(-1);
                })
                .catch(err => {
                    alert(err.response.data.message);
                });
        }
    },
    created() {
        this.getEvent();
    }
}
</script>

<style scoped>

.tickets {
    display: flex;
}

.tickets div {
    flex: 1 1 0;
}

.ticket {
    padding: 2rem;
    display: flex;
    align-items: center;
    border: 2px solid black;
    border-radius: var(--border2);
}

.ticket.disabled {
    cursor: not-allowed;
    border-color: gray;
    color: gray;
}

.info {
    margin-left: 1rem;
}

.info h4, .info p {
    margin: 0;
}

.workskop {
    margin: 5px 0;
}

.order {
    display: flex;
    justify-content: flex-end;
}

button.disabled {
    color: gray;
    border-color: gray;
    cursor: not-allowed;
}

</style>