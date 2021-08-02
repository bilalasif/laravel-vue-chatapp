<template>
  <app-layout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <ChatRoomSelection
          v-if="currentRoom.id"
          :rooms="chatRooms"
          :currentRoom="currentRoom"
          v-on:roomchanged="setRoom($event)"
        />
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <MessageContainer :messages="messages" />
          <InputMesage :room="currentRoom" v-on:messsagesent="getMessages" />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import MessageContainer from "./messageContainer.vue";
import InputMesage from "./inputMessage.vue";
import ChatRoomSelection from "./chatRoomSelection.vue";
export default {
  components: {
    AppLayout,
    MessageContainer,
    InputMesage,
    ChatRoomSelection,
  },
  data: function () {
    return {
      chatRooms: [],
      currentRoom: [],
      messages: [],
    };
  },
  watch: {
    currentRoom(val,oldVal) {
      if(oldVal.id){
        this.disconnect(oldVal)
      }
      this.connect();
    },
  },
  methods: {
    getRooms() {
      axios
        .get("/chat/rooms")
        .then((response) => {
          console.log("response", response);
          this.chatRooms = response.data;
          console.log("chatRooms", this.chatRooms);
          this.setRoom(response.data[0]);
        })
        .catch((error) => {
          console.log("error:" + error);
        });
    },
    setRoom(room) {
      this.currentRoom = room;
      console.log("currentRoom", this.currentRoom);
    },
    getMessages() {
      axios
        .get("/chat/room/" + this.currentRoom.id + "/messages")
        .then((response) => {
          this.messages = response.data;
        })
        .catch((error) => {
          console.log("error:" + error);
        });
    },
    connect() {
      if (this.currentRoom.id) {
        let vm = this;
        this.getMessages();
        window.Echo.private("chat." + this.currentRoom.id).listen(
          // ".message.new",
          "NewChatMessage",
          (e) => {
            vm.getMessages();
          }
        );
      }
    },
    disconnect(room) {
      window.Echo.leave("chat." + room.id);
    },
  },
  created() {
    this.getRooms();
  },
};
</script>
