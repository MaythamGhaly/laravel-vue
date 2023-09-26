<script setup>
import { ref } from 'vue'

const certifications = ref([])
const users = ref([])
const certificate = ref('')
const success = ref('')
const error = ref('')


fetch('http://127.0.0.1:8000/api/get-users', {
    method: 'GET',
    headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${localStorage.getItem('token')}`
    },
})
    .then(response => response.json())
    .then(data => {
        users.value = data
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
        certifications.value = data
    })
    .catch((e) => {
        console.log(e)
    });

async function addCertificateName() {
    await fetch('http://127.0.0.1:8000/api/add-certifications', {
        method: 'POST',

        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({
            certificate_name: certificate.value,
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.message == 'Certificate already exist') {
                error.value = 'Certificate already exist'
            }
            if (data.message == 'Certificate added') {
                certifications.value.push(data.certification)
                certificate.value = ''
                success.value = 'Certificate added'
            }

        })
        .catch((e) => {
            console.log(e)
        });
}

function deleteCertificate(certificate) {
    fetch('http://127.0.0.1:8000/api/delete-certification', {
        method: 'POST',

        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({
            certificate_id: certificate.id,
        })
    })
        .then(response => response.json())
        .then(data => {

            if (data.message == 'Certificate deleted') {
                console.log(data.message)
                certifications.value = certifications.value.filter(function (el) { return el.id != certificate.id; });
                success.value = 'Certificate deleted'
            }
            else {
                error.value = data.message
            }
        })
        .catch((e) => {
            console.log(e)
        });
}
</script>

<template>
    <div class="admin-home container">
        <h1>Certifications</h1>
        <form @submit.prevent="addCertificateName" class="add_certificate">
            <input type="text" v-model="certificate" placeholder="Enter Certificate name">
            <button class="submit">Add</button>
        </form>
        <div v-if="success" class="success">{{ success }}</div>
        <div v-if="error" class="error">{{ error }}</div>
        <div class="certificates">
            <div v-for="Certificate in certifications" :key="Certificate" class="certificate">
                <div class="certificate-name">
                    <h4>{{ Certificate.certificate_name }} </h4>
                    <span @click="deleteCertificate(Certificate)" class="close">x</span>
                </div>
                <h4 class="user-count">{{ Certificate.user_count }} users</h4>
            </div>
            <div v-if="certifications.length == 0">You don't add any certificate yet</div>
        </div>
        <div class="line"></div>
        <h1>Users</h1>
        <div class="users">
            <!-- <router-link :to="{ name: 'userInfo' }" class="link"> -->
            <div class="user" v-for="user in users" :key="user">
                <h5>Name :{{ user.name }}</h5>
                <h5>Email :{{ user.email }}</h5>
                <h5>Sex :{{ user.sex }}</h5>
                <h5>Blood type :{{ user.blood_type }}</h5>
                <h5>Last Login :{{ user.last_login }}</h5>
                <div v-if="user.approve == 1" class="approved"></div>
                <div v-if="user.approve == 0" class="not-approved"></div>
                <button v-if="user.approve == 0">approve</button>
                <button >Delete</button>
            </div>
        </div>
        <!-- </router-link> -->
    </div>
</template>

<style>
.admin-home .certificates {
    display: flex;
    justify-content: space-evenly;
    gap: 10px;
    max-width: 50vw;
    flex-wrap: wrap;
}

.add_certificate {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.certificate-name {
    display: flex;
    gap: 10px;
    padding: 5px 10px;
    border-radius: 10px;
    background-color: rgb(83, 83, 83);
}

.close {
    cursor: pointer;
    color: rgb(192, 192, 192);
    font-size: 20px;
    position: relative;
    top: -15px;
    right: -12px;
}

.user-count {
    color: rgb(192, 192, 192);
    font-size: 15px;
    font-weight: bold;
    margin-left: 10px;
}
.users{
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    gap: 10px;
    width: 100%;
    flex-wrap: wrap;
}
.user {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    width: 35%;
    min-width: 205px;
    flex-wrap: wrap;
    background-color: rgb(100, 100, 100);
    padding: 10px 15px;
    border-radius: 10px;
}

.approved {
    height: 15px;
    width: 15px;
    background-color: #0aee3b;
    border-radius: 50%;
    display: inline-block;
}

.not-approved {
    height: 15px;
    width: 15px;
    background-color: #ee0a0a;
    border-radius: 50%;
    display: inline-block;
}
</style>