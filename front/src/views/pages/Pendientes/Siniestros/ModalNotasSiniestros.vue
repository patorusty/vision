<template>
  <v-card>
    <v-card-title v-if="!edicion4">Agregar Nota</v-card-title>
    <v-card-title v-else>Editar Nota</v-card-title>
    <v-card-text>
      <v-form ref="form">
        <v-row>
          <v-col cols="4">
            <v-text-field
              label="Fecha"
              :value="dateToString(nota_siniestro.fecha)"
              v-mask="'##/##/####'"
              @change="nota_siniestro.fecha = stringToDate($event)"
              @click:clear="$nextTick(() => (nota_siniestro.fecha = null))"
              clearable
            />
          </v-col>
        </v-row>
        <v-textarea
          rows="3"
          v-model="nota_siniestro.nota"
        ></v-textarea>
      </v-form>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <v-btn
        class="mb-2"
        color="red"
        text
        @click="closeModal"
      >Cerrar</v-btn>
      <v-btn
        class="mb-2"
        v-if="!edicion4"
        color="green"
        text
        native-type="submit"
        @click="create"
      >Crear</v-btn>
      <v-btn
        class="mb-2"
        v-else
        @click="update"
        text
        color="green"
      >Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../helpers";

export default {
  mixins: [helpers],
  data: () => ({}),
  computed: {
    ...mapState("modal", ["modal4", "edicion4"]),
    ...mapState("nota_siniestro", ["nota_siniestro"]),
    ...mapState("siniestro", ["siniestro"]),
    ...mapState("usuario", ["validated_user"])
  },
  methods: {
    ...mapActions("nota_siniestro", ["createNota", "updateNota"]),
    ...mapMutations("modal", ["HIDE_MODAL4"]),
    ...mapMutations("nota_siniestro", ["RESET_NOTA"]),
    async create() {
      if (this.$refs.form.validate()) {
        this.nota_siniestro.user_id = this.validated_user.id;
        this.nota_siniestro.siniestro_automotor_id = this.siniestro.id;
        const createResult = await this.createNota(this.nota_siniestro);
        if (createResult) {
          this.closeModal();
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        const udpateResult = await this.updateNota(this.nota_siniestro);
        if (udpateResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL4(false);
      this.RESET_NOTA();
      this.$refs.form.resetValidation();
    }
  },
  watch: {
    modal4() {
      if (!this.modal4) {
        this.closeModal();
      }
    }
  }
};
</script>

<style>
</style>