<template>
  <div>{{ message.user.name }}:{{ message.message }}</div>
</template>
<script>
export default {
  name: "MessageItem",
  props: ["message"],
};
</script>