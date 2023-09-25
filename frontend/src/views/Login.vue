<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router';
const router = useRouter();

const email = ref('')
const password = ref('')

const error = ref('')

async function   signin ()  {
    await  fetch('http://127.0.0.1:8000/api/login', {
        method: 'POST',
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
            email: email.value,
            password: password.value,
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.token)
        if (data.token) {
            localStorage.setItem('token', data.token)
            router.push({ name: 'signup' })
        }
    })
    .catch((e) => {
        console.log(e)
        error.value = 'Invalid credentials'
    });
}
</script>

<template>
    <form @submit.prevent="signin" class="login-form">
        <h1>Login</h1>

        <label>Email</label>
        <input type='email' v-model="email" required>

        <label>Password</label>
        <input type='password' v-model="password" required>
        
        <div v-if="error" class="error">{{ error }}</div>
        <div class="submit">
            <button>Login</button>
        </div>
        <router-link :to="{ name: 'signup' }" class="link">
            <h5>Signup</h5>
        </router-link>
    </form>
</template>

<style>
.login-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid white;
    border-radius: 10px;
    padding: 20px 80px;
    background-color: rgb(65, 65, 65);
    margin-bottom: 15px;
}

.login-form label {
    color: white;
    font-size: 20px;
}

.login-form input {
    margin: 5px;
    width: 100%;
    height: 40px;
    border: none;
    border-radius: 10px;
    border-bottom: 1px solid rgb(173, 173, 173);
    background-color: rgb(226, 226, 226);
}

.login-form button {
    padding: 10px 20px;
    border: 0;
    margin-top: 20px;
    border-radius: 20px;
    background-color: rgb(226, 226, 226);
}

.login-form button:hover {
    background-color: rgb(173, 173, 173);
}

.login-form .submit {
    text-align: center;
}
.login-form .error{
    color: red;
    font-size: 12px;
}
</style>