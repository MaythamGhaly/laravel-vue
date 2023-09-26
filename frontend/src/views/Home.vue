<script setup>

import { ref } from 'vue'
import { useRouter } from 'vue-router';
const router = useRouter();

const user = JSON.parse(localStorage.getItem('user'))
var certificates = ref([])
var myCertifications = ref([])
var selectedOption = ref('Select a certificate')
const error = ref('')

fetch('http://127.0.0.1:8000/api/get-user-certifications', {
    method: 'GET',
    headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem('token')}`
    },
})
    .then(response => response.json())
    .then(data => {
        myCertifications.value = data
    })
    .catch((e) => {
        console.log(e)
    });

fetch('http://127.0.0.1:8000/api/get-certifications', {
    method: 'GET',
    headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem('token')}`
    },
})
    .then(response => response.json())
    .then(data => {
        certificates.value = data
    })
    .catch((e) => {
        console.log(e)
    });

function deleteCertificate(certificate) {
    fetch('http://127.0.0.1:8000/api/delete-user-certification', {
        method: 'POST',

        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({
            certificate_id: certificate.certificate_id,
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log(data)
            if (data.message == 'User certificate deleted') {
                myCertifications.value = myCertifications.value.filter(function (el) { return el.id != certificate.id; });
            }
        })
        .catch((e) => {
            console.log(e)
        });
}
async function addCertificate() {
    await fetch('http://127.0.0.1:8000/api/add-user-certifications', {
        method: 'POST',

        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${localStorage.getItem('token')}`,
        },
        body: JSON.stringify({
            certificate_id: selectedOption.value.id,
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.message == 'User certificate already exists') {
                error.value = data.message
                selectedOption.value = 'Select a certificate'
            }
            else {
                myCertifications.value.push(selectedOption.value)
                selectedOption.value = 'Select a certificate'
            }
        })
        .catch((e) => {
            console.log(e.message)
        });
}

function logout() {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push({ name: 'login' })
}
</script>

<template>
    <div class="home container">
        <h1>Your Profile</h1>
        <h4>Name: <span> {{ user.name }} </span></h4>
        <h4>Email: <span> {{ user.email }}</span></h4>
        <h4>Sex: <span>{{ user.sex }}</span></h4>
        <h4>Blood type: <span>{{ user.blood_type }}</span></h4>
        <router-link :to="{ name: 'profile' }" class="link">
            <button>Edit Profile</button>
        </router-link>
        <div class="line"></div>
        <h1>Your certificates</h1>
        <div class="certificates">
            <div v-for="myCertificate in myCertifications" :key="myCertificate" class="certify">{{
                myCertificate.certificate_name }} <span @click="deleteCertificate(myCertificate)">x</span> </div>
            <div v-if="myCertifications.length == 0">You don't have any certificate</div>
        </div>
        <h1>Add Certificate</h1>
        <select v-model="selectedOption" @change="addCertificate">
            <option value="Select a certificate" disabled>Select a certificate</option>
            <option v-for="certificate in certificates" :key="certificate" :value="certificate">{{
                certificate.certificate_name }}</option>
        </select>
        <div v-if="error" class="error">{{ error }}</div>

        <div class="line"></div>
        <button @click="logout">Logout</button>
    </div>
</template>

<style>
.home span {
    color: rgb(255, 255, 255);
}

.home h4 {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    min-width: 200px;
}

.home .certificate span {
    color: rgb(255, 255, 255);
    margin-left: 10px;
    cursor: pointer;
}

.certify {
    display: flex;
    gap: 10px;
    padding: 5px 10px;
    border-radius: 10px;
    background-color: rgb(83, 83, 83);
}
.certify span {
    color: rgb(255, 255, 255);
    margin-left: 10px;
    cursor: pointer;
}
</style>