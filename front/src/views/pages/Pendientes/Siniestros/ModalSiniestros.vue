<template>
  <v-card>
    <v-row>
      <v-col>
        <v-card-title>
          <span>Siniestro</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="form">
              <v-row>
                <v-col class=" mt-0 py-0">
                  <v-text-field class="input-numero" v-model="siniestro.numero_siniestro"
                    label="Número de Siniestro"></v-text-field>
                </v-col>
                <v-col cols="4" class=" mt-0 py-0">
                  <v-select label="Tipo de Reclamo" v-model="siniestro.tipo_reclamo" :items="tipo_reclamos"
                    item-text="value" item-value="value" :rules="[rules.required]">
                  </v-select>
                </v-col>
                <v-col class=" mt-0 py-0">
                  <v-menu v-model="fechaDenuncia" :close-on-content-click="false" transition="scale-transition" offset-y
                    min-width="290px">
                    <template v-slot:activator="{ on }">
                      <v-text-field :value="dateToString(siniestro.fecha_denuncia)" @click:clear="
                        $nextTick(() => (siniestro.fecha_denuncia = null))
                        " clearable label="Fecha de Denuncia" v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="siniestro.fecha_denuncia" clearable color="primary lighten-1" no-title
                      scrollable locale="es-la" @input="fechaDenuncia = false">
                    </v-date-picker>
                  </v-menu>
                </v-col>

                <v-col class=" mt-0 py-0">
                  <v-menu v-model="fechaSinistestro" :close-on-content-click="false" transition="scale-transition"
                    offset-y min-width="290px">
                    <template v-slot:activator="{ on }">
                      <v-text-field :value="dateToString(siniestro.fecha_siniestro)" @click:clear="
                        $nextTick(() => (siniestro.fecha_siniestro = null))
                        " clearable label="Fecha de Siniestro" v-on="on" :rules="[rules.required]"></v-text-field>
                    </template>
                    <v-date-picker v-model="siniestro.fecha_siniestro" clearable color="primary lighten-1" no-title
                      scrollable locale="es-la" @input="fechaSinistestro = false">
                    </v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>
              <v-row>

                <v-col class=" mt-0 py-0">
                  <v-menu v-model="fechaCompleto" :close-on-content-click="false" transition="scale-transition" offset-y
                    min-width="290px">
                    <template v-slot:activator="{ on }">
                      <v-text-field :value="dateToString(siniestro.fecha_completo)" @click:clear="
                        $nextTick(() => (siniestro.fecha_completo = null))
                        " clearable label="Completo" v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="siniestro.fecha_completo" clearable color="primary lighten-1" no-title
                      scrollable locale="es-la" @input="fechaCompleto = false">
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col class=" mt-0 py-0">
                  <v-select label="Estado de Siniestro" v-model="siniestro.estado_siniestro" :items="estados"
                    item-text="value" item-value="value" :rules="[rules.required]">
                  </v-select>
                </v-col>
                <v-col class=" mt-0 py-0 d-flex justify-center">
                  <v-checkbox v-model="siniestro.inspeccion" label="Inspección"></v-checkbox>
                </v-col>
                <v-col class=" mt-0 py-0 d-flex justify-center">
                  <v-checkbox v-model="siniestro.en_taller" label="En Taller"></v-checkbox>
                </v-col>
              </v-row>
              <v-row>
                <v-col class=" mt-0 py-0">
                  <v-select v-if="siniestro.poliza.riesgo_automotor.length" label="Riesgo" v-model="siniestro.riesgo_id"
                    :items='siniestro.poliza.riesgo_automotor' :item-text="patenteYcobertura" item-value="id"></v-select>
                  <v-select v-else label="Riesgo" v-model="siniestro.riesgo_id" :items='poliza.riesgo_automotor'
                    :item-text="patenteYcobertura" item-value="id"></v-select>
                </v-col>
                <v-col class=" mt-0 py-0">
                  <v-text-field label="Compania del Tercero" v-model="siniestro.compania_tercero"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col class=" mt-0 py-0">
                  <v-textarea v-model="siniestro.detalle" class="mt-0 pt-0" label="Detalle"></v-textarea>
                </v-col>
              </v-row>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions class="d-flex justify-end">
          <v-btn class="mb-2" color="red" text @click="closeModal">Cerrar</v-btn>
          <v-btn class="mb-2" v-if="!edicion3" color="green" @click="create" text native-type="submit">Crear</v-btn>
          <v-btn class="mb-2" v-else @click="update" text color="green">Guardar</v-btn>
        </v-card-actions>
      </v-col>
      <v-expand-x-transition>
        <v-col v-if="edicion3" cols='5'>
          <notas-siniestros />
        </v-col>
      </v-expand-x-transition>
    </v-row>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../helpers";
import NotasSiniestros from "./NotasSiniestros.vue";

export default {
  mixins: [helpers],
  components: { NotasSiniestros },
  data: () => ({
    fechaDenuncia: false,
    fechaSinistestro: false,
    fechaCompleto: false,
    headers: [
      { text: "Fecha", value: "fechaSiniestro" },
      { text: "Nota", value: "nota_siniestros.nota" },
      { text: "Actions", value: "actions", sortable: false, align: "right" }
    ]
  }),
  computed: {
    ...mapState("siniestro", ["siniestro", "tipo_reclamos", "estados"]),
    ...mapState("poliza", ["poliza"]),
    ...mapState("modal", ["modal", "modal3", "edicion3", "edicion"])
  },
  methods: {
    ...mapActions("siniestro", [
      "createSiniestro",
      "updateSiniestro",
      "updateSiniestroDePoliza"
    ]),
    ...mapMutations("siniestro", ["RESET_SINIESTRO"]),
    ...mapMutations("nota_siniestro", ["RESET_LISTA"]),
    ...mapMutations("modal", [
      "SHOW_MODAL",
      "HIDE_MODAL3",
      "HIDE_MODAL",
      "SWITCH_EDICION"
    ]),
    closeModal() {
      this.HIDE_MODAL3(false);
      this.RESET_SINIESTRO();
      this.RESET_LISTA();
      this.$refs.form.resetValidation();
    },
    async create() {
      if (this.$refs.form.validate()) {
        this.siniestro.poliza_id = this.poliza.id;
        const createResult = await this.createSiniestro(this.siniestro);
        if (createResult) {
          this.SWITCH_EDICION(true);
          // this.$store.modal.edicion3 = false;
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        var updateResult = false;
        if (this.poliza.id != null) {
          updateResult = await this.updateSiniestroDePoliza(this.siniestro);
        } else {
          updateResult = await this.updateSiniestro(this.siniestro);
        }
        if (updateResult) {
          this.closeModal();
        }
      }
    }
  },
  watch: {
    modal3() {
      if (!this.modal3) {
        this.closeModal();
      }
    }
  }
};
</script>

<style scoped>
.input-numero .v-text-field__slot input {
  font-weight: bold !important;
  color: #e91e63 !important;
}
</style>