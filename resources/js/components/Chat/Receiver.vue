<template>
     <li class=" receiver-message-line">
                    <div class="message-text" v-html="message.content">
                    </div>
                    <div class="message-time">
                    
                        {{ message.time_ago }}
                    </div>
    </li>
</template>

<script>
export default {
    name: "Receiver",
    props: ["message"]
}
</script>

<style scoped>
.receiver-message-line {
    margin-left: -1.5rem ;
    width: 16rem;
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
    background-color: #fefefe;
    padding: 0.5rem;
    transition: all 0.3s ease; 
    border-radius: 10px ;
}
.message-text {
    padding: 0.50rem;
    text-align: left;
    font-size: 0.875rem; 
}
.message-time {
    text-align: right;
    margin-top: 0.75rem;
    font-size: 0.70rem;
}

</style>
