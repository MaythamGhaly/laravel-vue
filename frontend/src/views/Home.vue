<script setup>

import { ref } from 'vue'

const user = JSON.parse(localStorage.getItem('user'))
var certificates = ref([])
var myCertifications = ref([])
const selectedOption = ref('')
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

function addCertificate() {
    console.log(selectedOption.value.id)
    fetch('http://127.0.0.1:8000/api/add-certifications', {
        method: 'POST',

        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({
            certificate_id: selectedOption.value.id,
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.message == 'User certificate already exists') {
                error = data.message
            }
            else {
                myCertifications.value.push(selectedOption)
            }
        })
        .catch((e) => {
            console.log(e)
        });
}
</script>

<template>
    <div class="home">
        <h1>Your Profile</h1>
        <h4>Name: <span> {{ user.name }} </span></h4>
        <h4>Email: <span> {{ user.email }}</span></h4>
        <h4>Sex: <span>{{ user.sex }}</span></h4>
        <h4>Blood type: <span>{{ user.blood_type }}</span></h4>
        <router-link :to="{ name: 'profile' }" class="link">
            <button>Edit Profile</button>
        </router-link>
        <h1>Your certificates</h1>
        <div v-for="myCertificate in myCertifications" :key="myCertificate" class="certificate">{{ myCertificate.certificate_name }} <span
                @click="deleteCertificate(myCertificate)">x</span> </div>
        <div v-if="myCertifications.length == 0">You don't have any certificate</div>
        <h1>Add Certificate</h1>
        <select v-model="selectedOption" @change="addCertificate">
            <option value="Select a certificate" disabled selected>Select a certificate</option>
            <option v-for="certificate in certificates" :key="certificate" :value=" certificate">{{
                certificate.certificate_name }}</option>
        </select>
        <div v-if="error" class="error">{{ error }}</div>

    </div>
</template>

<style> .home {
     display: flex;
     flex-direction: column;
     gap: 10px;
     align-items: center;
     justify-content: center;
     background-color: rgb(65, 65, 65);
     padding: 20px 80px;
     border: 1px solid white;
     border-radius: 10px;
     margin: 20px;
 }

 .home span {
     color: rgb(255, 255, 255);
 }

 .home h4 {
     display: flex;
     flex-direction: row;
     justify-content: space-between;
     min-width: 200px;
 }

 .home button {
     padding: 10px 20px;
     border: 0;
     margin-top: 20px;
     border-radius: 20px;
     background-color: rgb(226, 226, 226);
 }

 .home button:hover {
     background-color: rgb(173, 173, 173);
 }

 .home select {
     align-items: center;
     margin: 5px;
     width: 100%;
     height: 40px;
     border-radius: 10px;
     background-color: rgb(226, 226, 226);
 }

 .home .certificate {
     padding: 5px 10px;
     border-radius: 10px;
     background-color: rgb(83, 83, 83);
     ;
 }

 .home .certificate span {
     color: rgb(255, 255, 255);
     margin-left: 10px;
     cursor: pointer;
 }
 .erorr{
    color: red;
    font-size: 12px;
 }
 </style>