<template>
  <v-snackbar
    class="snack"
    :value="snackbar"
    :color="color"
    right
    top
    timeout="3000"
  >
    {{ snackText }}
    <template v-slot:action="{ attrs }">
      <v-btn
        color="blue"
        text
        v-bind="attrs"
        @click="CLOSE_SNACK"
      >
        <v-icon color="white"> mdi-close-circle </v-icon>
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
import { mapMutations, mapState } from "vuex";

export default {
  name: "Notifications",
  computed: {
    ...mapState("snackbar", ["snackbar", "color", "snackText"])
  },
  methods: {
    ...mapMutations("snackbar", ["CLOSE_SNACK"])
  }
};
</script>

<style>
.snack {
  padding-top: 0 !important;
}
</style>