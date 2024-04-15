<template>
    <div class="chat-panel" style="border:">
        <header class="headers">
            <!-- <i class="fas fa-solid fa-times shrink mx-2 text-white cursor-pointer" title="close"
            @click="$emit('onCloseChat', user)"></i> -->
            <div style="
            flex: 2;
            flex-grow: 1;
            flex-basis: 50%;
            color: white ;
            margin-left: .5rem;
            ">{{ user.full_name }}</div>
            <CloseOutlined  @click="$emit('onCloseChat', user)" style="color: whitesmoke; font-size: large; cursor: pointer; margin-right: .5rem;" />
        </header>
        <section class="chat-panel-content" ref="chatContentRef"
        @scroll="handleChatScroll" >

            <!-- <i class="fas fa-circle-notch fa-spin absolute left-36 top-16 text-3xl" v-if="loading"></i> -->
            <LoadingOutlined v-if="loading" />

            <ul v-else>
                <MessageLine v-for="userMessage in userMessages" :key="userMessage.id" :message="userMessage" :auth="auth" />
            </ul>

        </section>

        <!-- <EmojiSelect v-if="emojiBtnClicked" @onSelect="handleSelectEmoji" @onClose="emojiBtnClicked = false" /> -->

        <footer class="flex flex-row items-center chat-panel-footer">
            <SendOutlined style="cursor: pointer; font-size: 1.7rem; margin-left: 1rem; color: white;" @click="submitMessage" />

            <!-- <button class="text-xl font-bold text-gray-600 mx-1" type="button" title="add emoji" >&#128512;</button> -->
            <textarea name="currentMessage" style="resize: vertical; padding: 0.5rem;  border-width: 1px; border-style: solid;  border-color: #cbd5e0; width: 100%; margin-left: 1rem" v-model="messageContent" ></textarea>
        </footer>
    </div>
</template>

<script>
import {ref, watch} from "vue";
import axios from "../../axios"
import _ from "lodash";

import MessageLine from "./MessageLine.vue";

export default {
    name: "ChatPanel",
    components: {MessageLine},
    props: ["user", "emittedMessage", "auth"],
    emits: ["onCloseChat"],
    setup(props) {

        const { user } = props;

        const chatContentRef = ref(null);

        const messageContent = ref("");
        const userMessages = ref([]);
        let scrollPoint = ref(0);
        const loading = ref(false);


        const submitMessage = () =>
        {
            const payload = {
                receiver_id: user.id,
                message_content: messageContent.value
            };
            axios.post("backend/messages",payload)
                .then(response => { 
                    userMessages.value.push(response.data.data);
                    messageContent.value = "";
                    scrollToChatBottom();
                 
                })
                .catch(function (error) {
                  console.log(error)
                });
        }



        const getMessages = () =>{
            loading.value = true
            axios.get(`backend/messages?receiver_id=${user.id}`)
                .then(response => { 
                    userMessages.value = response.data.data.reverse();
                    loading.value = false
                    scrollToChatBottom();
                })
                .catch(function (error) {
                  console.log(error)
                  loading.value = false
                });
    
        }

        const scrollToChatBottom = () =>
        {
            setTimeout(() => {
                if(chatContentRef && chatContentRef.value) {
                    chatContentRef.value.scrollTop = chatContentRef.value.scrollHeight;

                    scrollPoint.value = chatContentRef.value.scrollTop;
                }
            }, 100);
        }



        const handleChatScroll = _.debounce((e) => {
                // if the user scrolls to top
                // if(e.target.scrollTop - 50 < scrollPoint.value) {
                //     // showLoading();

                //     const oldMessage = userMessages.value[0];

                //     window.axios.get(`/messages?receiver_id=${user.id}&earlier_date=${oldMessage.created_at}`)
                //         .then(response => {
                //             if(response && response.data.messages) {
                //                 const filtered = [];

                //                 response.data.messages.reverse().forEach(message => {
                //                     if(!userMessages.value.find(m => m.id == message.id)) {
                //                         filtered.push(message);
                //                     }
                //                 });

                //                 userMessages.value = [...filtered, ...userMessages.value];
                //             }

                //             setTimeout(() => {
                //                 hideLoading();
                //             }, 2000);

                //         }).catch(error => {
                //             setTimeout(() => {
                //                 hideLoading();
                //             }, 2000);

                //         console.error(error.response);
                //     });
                // }

                scrollPoint.value = e.target.scrollTop;
        }, 1000);




        getMessages();

        watch(() => props.emittedMessage, (newMessage, oldMessage) => {
            if(newMessage) {
                const isMessageExist = userMessages.value.find(m => m.id == newMessage.id);

                if(!isMessageExist) {
                    userMessages.value.push(newMessage);
                    scrollToChatBottom();
                }
            }
        });

        return {
            loading,

            userMessages,
            messageContent,
            handleChatScroll,
            chatContentRef,
            submitMessage,

        }
    }
}
</script>

<style scoped>
.chat-panel {
    display: flex;
    flex-direction: column;
    display: inline-block;
    position: relative;
    margin-left: 0.625rem; /* Adjust as needed */
    margin-right: 0.625rem; /* Adjust as needed */
    width: 22rem; /* Adjust as needed */
    z-index: 99999;
}
.headers{
    padding-left: 0.5rem; /* Adjust as needed */
  padding-right: 0.5rem; /* Adjust as needed */
  padding-top: 1rem; /* Adjust as needed */
  padding-bottom: 1rem; /* Adjust as needed */
  display: flex;
  flex-direction: row;
  align-items: center;
  background-color: #2c7dc4; /* Adjust as needed */
}

.chat-panel-content{
    padding-left: 0.5rem; /* Adjust as needed */
  padding-right: 0.5rem; /* Adjust as needed */
  padding-top: 1rem; /* Adjust as needed */
  padding-bottom: 1rem; /* Adjust as needed */
  height: 24rem; 
  background: #f1efef;
  overflow-y: scroll;
}
.chat-panel-footer{
    display: flex;
    flex-direction: row;
    align-items: center;
    background: #2c7dc4;
}


.send-btn{
    padding-left: 0.25rem; /* Adjust as needed */
  padding-right: 0.25rem; /* Adjust as needed */
  height: 100%; /* Adjust as needed */
  background-color: #4a90e2; /* Adjust as needed */
  color: white;
  align-items: center;
  display: flex;
}
</style>