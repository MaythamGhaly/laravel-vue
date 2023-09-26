<script setup>

import { ref } from 'vue'
import { useRouter } from 'vue-router';
const router = useRouter();

const user = JSON.parse(localStorage.getItem('user'))
const name = ref(user.name)
const email = ref(user.email)
const password = ref('')
const sex = ref(user.sex)
const blood_type = ref(user.blood_type)
const passwordError = ref('')
const error = ref('')
const success = ref('')

async function editProfile (){
    if (password.value.length > 0) {
        passwordError.value = password.value.length > 8 ? '' : 'Password must be at least 8 characters'
    }
    if (!passwordError.value) {
        await fetch('http://127.0.0.1:8000/api/edit-profile', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${localStorage.getItem('token')}`
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
                success.value = data.message
                router.push({ name: 'home' })
                localStorage.removeItem('user')
                localStorage.setItem('user', JSON.stringify(data.user) )

            })
            .catch((e) => {
                error.value = 'Something went wrong'
            });
    }
}
</script>

<template>
    <form @submit.prevent="editProfile" class="Profile-form container">
        <h1>Your Profile</h1>
        <div class="line"></div>

        <label>Name</label>
        <input type='name' v-model="name" required>

        <label>Email</label>
        <input type='email' v-model="email" disabled>

        <label>Password</label>
        <input type='password' v-model="password">
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

        <div v-if="error" class="error">{{ error }}</div>
        <div v-if="success" class="success">{{ success }}</div>
        <div class="submit">
            <button>Edit Profile</button>
        </div>
    </form>
</template>

<style>
.Profile-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid white;
    border-radius: 10px;
    padding: 20px 80px;
    background-color: rgb(65, 65, 65);
    margin-bottom: 15px;
    min-width: 400px;
}

.Profile-form label {
    color: white;
    font-size: 20px;
}

.Profile-form input {
    margin: 5px;
    width: 100%;
    height: 40px;
    border: none;
    border-radius: 10px;
    border-bottom: 1px solid rgb(173, 173, 173);
    background-color: rgb(226, 226, 226);
}

.Profile-form select {
    align-items: center;
    margin: 5px;
    width: 100%;
    height: 40px;
    border-radius: 10px;
    background-color: rgb(226, 226, 226);
}

.Profile-form input[type="checkbox"] {
    display: inline-block;
    width: 16px;
    margin: 0 10px 0 0;
    position: relative;
    top: 2px;
    height: 16px;
    background-color: rgb(226, 226, 226);
}

.Profile-form option {
    background-color: rgb(226, 226, 226);
}

.Profile-form .submit {
    text-align: center;
}

.Profile-form .error {
    color: red;
    font-size: 12px;
}
.success{
    color: green;
    font-size: 12px;
}

.link {
    color: white;
    text-decoration: none;
    margin-top: 10px;
}
</style>