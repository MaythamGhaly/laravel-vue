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
        console.log(data)
        if (data.message == 'User not approved'){
            error.value = data.message
        }
        else if (data.token && data.user.role == 'user') {
            localStorage.setItem('token', data.token )
            localStorage.setItem('user', JSON.stringify(data.user) )
            router.push({ name: 'home' })
        }
        else if (data.user.role == 'admin') {
            localStorage.setItem('token', data.token )
            router.push({ name: 'admin' })
        }
        else {
            error.value = data.message
        }
    })
    .catch((e) => {
        console.log(e)
        error.value = 'Invalid credentials'
    });
}
</script>

<template>
    <form @submit.prevent="signin" class="login-form container">
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

.login-form .submit {
    text-align: center;
}
.login-form .error{
    color: red;
    font-size: 12px;
}

</style>