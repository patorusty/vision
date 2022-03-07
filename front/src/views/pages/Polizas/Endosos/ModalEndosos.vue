<template>
  <v-card>
    <v-card-title>
      <span>Endosos</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col
              cols="6"
              class=" mt-0 py-0"
            >
              <v-menu
                v-model="fechaSolicitud"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    :value="dateToString(endoso.fecha_solicitud)"
                    @click:clear="
                          $nextTick(() => (endoso.fecha_solicitud = null))
                        "
                    clearable
                    label="Fecha de Solicitud"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="endoso.fecha_solicitud"
                  clearable
                  color="primary lighten-1"
                  no-title
                  scrollable
                  locale="es-la"
                  @input="fechaSolicitud = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-row>
            <v-col class=" mt-0 py-0">
              <v-select
                label="Tipo Endoso"
                v-model="endoso.tipo_endoso_id"
                :items="tipo_endosos"
                item-text="nombre"
                item-value="id"
                :rules="[rules.required]"
              >
              </v-select>
            </v-col>
            <v-col class=" mt-0 py-0">
              <v-select
                label="Detalle Endoso"
                v-model="endoso.detalle_endoso_id"
                :items="detalle_endosos_por_tipo"
                item-text="nombre"
                item-value="id"
                :rules="[rules.required]"
              >
              </v-select>
            </v-col>
          </v-row>

          <v-row>
            <v-col class=" mt-0 py-0">
              <v-menu
                v-model="fechaEmision"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    :value="dateToString(endoso.fecha_emision)"
                    @click:clear="
                          $nextTick(() => (endoso.fecha_emision = null))
                        "
                    clearable
                    label="Fecha de Emisión"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="endoso.fecha_emision"
                  clearable
                  color="primary lighten-1"
                  no-title
                  scrollable
                  locale="es-la"
                  @input="fechaEmision = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col class=" mt-0 py-0">
              <v-text-field
                v-model="endoso.numero_endoso"
                label="Número de Endoso"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class=" mt-0 py-0">
              <v-checkbox
                v-model="endoso.completo"
                label="Completo"
              ></v-checkbox>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
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
        v-if="!edicion2"
        color="green"
        @click="create"
        text
        native-type="submit"
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
  data: () => ({
    fechaSolicitud: false,
    fechaEmision: false
  }),
  computed: {
    ...mapState("endoso", ["endoso", "tipo_endosos", "detalle_endosos"]),
    ...mapState("modal", ["modal2", "edicion2"]),
    ...mapState("poliza", ["poliza"])
  },
  methods: {
    ...mapMutations("modal", ["HIDE_MODAL2"]),
    ...mapMutations("endoso", ["RESET_ENDOSO"]),
    ...mapActions("endoso", ["createEndoso", "updateEndoso"]),
    ...mapActions("poliza", ["updatePoliza"]),
    async create() {
      if (this.$refs.form.validate()) {
        this.endoso.poliza_id = this.poliza.id;
        const createResult = await this.createEndoso(this.endoso);
        if (this.endoso.tipo_endoso_id == 1) {
          this.poliza.estado_poliza_id = 1;
          await this.updatePoliza(this.poliza);
        }
        if (createResult) {
          this.closeModal();
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateEndoso(this.endoso);
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL2(false);
      this.RESET_ENDOSO();
      this.$refs.form.resetValidation();
      this.cpUsado = false;
    }
  },
  watch: {
    modal2() {
      if (!this.modal2) {
        this.closeModal();
      }
    }
  }
};
</script>