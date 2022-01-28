<template>
  <v-stepper v-model="step">
    <v-stepper-content step="1">
      <step-uno />
    </v-stepper-content>
    <v-stepper-content step="2">
      <step-dos />
    </v-stepper-content>
  </v-stepper>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import StepUno from "./StepsModal/StepUno.vue";
import StepDos from "./StepsModal/StepDos.vue";
import { helpers } from "../../../../../helpers";

export default {
  components: { StepUno, StepDos },
  data: () => ({}),
  computed: {
    ...mapState("modal", ["step"]),
    ...mapState("poliza", ["poliza"])
  },
  mixins: [helpers],
  methods: {
    ...mapMutations("poliza", ["RESET_POLIZA"])
  }
};
</script>

<style>
</style>