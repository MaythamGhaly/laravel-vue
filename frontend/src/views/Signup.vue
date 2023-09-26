<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router';
const router = useRouter();

const name = ref('')
const email = ref('')
const password = ref('')
const sex = ref('male')
const blood_type = ref('A+')
const terms = ref(false)
const passwordError = ref('')
const error = ref('')

async function signup() {
    passwordError.value = password.value.length > 8 ? '' : 'Password must be at least 8 characters'
    if (!passwordError.value) {
        await fetch('http://127.0.0.1:8000/api/register', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                name: name.value,
                email: email.value,
                password: password.value,
                sex: sex.value,
                blood_type: blood_type.value,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    router.push({ name: 'login' })
                }
                else {
                    error.value = data.message
                }
            })
            .catch((e) => {
                error.value = 'Something went wrong'
            });
    }

}
</script>

<template>
    <form @submit.prevent="signup" class="registration-form container">
        <h1>Registration</h1>

        <label>Name</label>
        <input type='name' v-model="name" required>

        <label>Email</label>
        <input type='email' v-model="email" required>

        <label>Password</label>
        <input type='password' v-model="password" required>
        <div v-if="passwordError" class="error">{{ passwordError }}</div>

        <label>Sex:</label>
        <select v-model="sex" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>

        <label>Blood Type:</label>
        <select v-model="blood_type" required>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>

        <div class="terms">
            <input type="checkbox" v-model="terms" required>
            <label for="terms">I agree to the terms of service</label>
        </div>

        <div v-if="error" class="error">{{ error }}</div>
        <div class="submit">
            <button>Create an Account</button>
        </div>
        <router-link :to="{ name: 'login' }" class="link">
            <h5>Login</h5>
        </router-link>
    </form>
</template>

<style>

.registration-form label {
    color: white;
    font-size: 20px;
}

.registration-form input {
    margin: 5px;
    width: 100%;
    height: 40px;
    border: none;
    border-radius: 10px;
    border-bottom: 1px solid rgb(173, 173, 173);
    background-color: rgb(226, 226, 226);
}

.registration-form select {
    align-items: center;
    margin: 5px;
    width: 100%;
    height: 40px;
    border-radius: 10px;
    background-color: rgb(226, 226, 226);
}

.registration-form input[type="checkbox"] {
    display: inline-block;
    width: 16px;
    margin: 0 10px 0 0;
    position: relative;
    top: 2px;
    height: 16px;
    background-color: rgb(226, 226, 226);
}

.registration-form option {
    background-color: rgb(226, 226, 226);
}

.registration-form button {
    padding: 10px 20px;
    border: 0;
    margin-top: 20px;
    border-radius: 20px;
    background-color: rgb(226, 226, 226);
}

.registration-form button:hover {
    background-color: rgb(173, 173, 173);
}

.registration-form .submit {
    text-align: center;
}

.registration-form .error {
    color: red;
    font-size: 12px;
}

.link {
    color: white;
    text-decoration: none;
    margin-top: 10px;
}
</style>