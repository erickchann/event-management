<template>
	<div class="home">
		<div class="container">
			<header>
				<h1>Event Booking Platform</h1>

				<router-link to="/login" class="login-btn" v-if="!loginData">Login</router-link>

				<div v-if="loginData" style="display: flex">
					<p style="margin-right: 1rem">{{ loginData.username }}</p>

					<button type="button" @click="logout" class="login-btn">Logout</button>
				</div>
			</header>

			<main>
				<router-link :to="`/organizer/${event.organizer.slug}/event/${event.slug}`" class="event" v-for="event in events" :key="event.id">
					<h2 class="title">{{ event.name }}</h2>
					<p>{{ event.organizer.name }}, {{ event.date }}</p>
				</router-link>
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
			events: ''
		}
	},
	methods: {
		getEvents() {
			axios.get(`events`)
				.then(res => {
					console.log(res.data.events);
					this.events = res.data.events;
				})
				.catch(err => {
					alert(err.response.data.message);
				});
		},
		logout() {
			axios.post(`logout?token=${this.loginData.token}`)
				.then(res => {
					localStorage.removeItem('login');

					this.loginData = null;
				})
				.catch(err => {
					alert(err.response.data.message);
				});
		}
	},
	created() {
		this.getEvents();
		console.log(this.loginData)
	},
}
</script>

<style scoped>


</style>