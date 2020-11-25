<template>
  <v-snackbar :value="snackbar" :color="color" right top>
    {{ snackText }}
    <v-icon color="white" aria-label="Close"> mdi-close-circle </v-icon>
  </v-snackbar>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "Notifications",
  computed: {
    ...mapState("snackbar", ["snackbar", "color", "snackText"]),
  },

};
</script>

<style>
</style>