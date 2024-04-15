<template>
     <li class=" sender-message-line">
        <div class="mb-2 text-end text-teal-700 message-username">
            You
        </div>
                    <div class="message-text" v-html="message.content">
                    </div>
                    <div class="message-time">
                      
                   {{ message.time_ago }}
                    </div>
    </li>
</template>

<script>
export default {
    name: "Sender",
    props: ["message"]
}
</script>

<style scoped>
.sender-message-line {
width: auto;
display: flex;
flex-direction: column;
color: white;
margin-bottom: 1rem; /* Adjust as needed */
background-color: #1b8cee; /* Adjust as needed */
padding: 0.5rem; /* Adjust as needed */
transition: all 0.3s ease;
border-radius: 10px ;

}

.message-text {
    padding: 0.50rem;
    text-align: right;
    font-size: 0.875rem; 
}
.message-time {
    color: black;
    margin-top: 0.75rem;
    font-size: 0.75rem;
}

.message-username {
    margin-bottom: 0.5rem; 
    text-align: right;
    color: #000000; 
}
</style>
