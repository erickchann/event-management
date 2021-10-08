<template>
    <div>
        <div class="container">
            <main>

                <h1>Login</h1>

                <table>
                    <tr>
                        <td>Lastname</td>
                        <td></td>
                        <td></td>
                        <td><input type="text" v-model="formData.lastname"></td>
                    </tr>
                    <tr>
                        <td>Registration Code</td>
                        <td></td>
                        <td></td>
                        <td><input type="text" v-model="formData.registration_code"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><button type="button" @click="login" class="login-btn">Login</button></td>
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
            formData: {
                lastname: '',
                registration_code: ''
            }
        }
    },
    methods: {
        login() {
            axios.post('login', this.formData)
                .then(res => {
                    localStorage.setItem('login', JSON.stringify(res.data));

                    this.$router.push('/');
                })
                .catch(err => {
                    alert('Lastname or registration code not correct');
                });
        } 
    },
}
</script>

<style scoped>

input[type=text] {
    border-radius: var(--border1);
    width: 200px;
}

button {
    padding: .2em 1em;
    width: 100%;
}

</style>