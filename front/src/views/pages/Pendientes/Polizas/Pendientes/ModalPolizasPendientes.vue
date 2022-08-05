<template>
  <v-stepper v-model="step">
    <v-stepper-content step="1">
      <step-uno />
    </v-stepper-content>
    <v-stepper-content step="2">
      <step-dos v-if="poliza.riesgo_aotomotor" />
    </v-stepper-content>
  </v-stepper>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import StepUno from "./StepsModalPendiente/StepUno.vue";
import StepDos from "./StepsModalPendiente/StepDos.vue";
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
    ...mapMutations("poliza", ["RESET_POLIZA"]),
    ...mapMutations("modal", ["HIDE_MODAL", "SET_STEP"]),
    closeModal() {
      this.HIDE_MODAL(false);
      this.RESET_POLIZA();
      this.SET_STEP(1);
    }
  },
  watch: {
    modal() {
      if (!this.modal) {
        this.closeModal();
      }
    }
  }
};
</script>

<style>
</style>