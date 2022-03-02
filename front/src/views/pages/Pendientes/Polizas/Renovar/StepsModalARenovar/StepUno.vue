  <template>
  <v-card height="60%">
    <!-- <v-card-title>
      <span>Endosos</span>
    </v-card-title> -->
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col>
              <div class="text-subtitle-1">
                {{"Cliente: " + poliza.cliente.nombre + " " +poliza.cliente.apellido}}
              </div>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-text-field
                label="Poliza Nro:"
                v-model="poliza.numero"
              >
              </v-text-field>
            </v-col>
            <v-col class="d-flex align-center">
              <div class="text-subtitle-1">
                {{'Vigencia: ' + dateToString(poliza.vigencia_desde) + " al " +dateToString(poliza.vigencia_hasta)}}
              </div>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-menu
                v-model="calendarioEmision"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    :value="dateToString(poliza.fecha_emision)"
                    @click:clear="
                          $nextTick(() => (poliza.fecha_emision = null))
                        "
                    clearable
                    label="Emision"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="poliza.fecha_emision"
                  clearable
                  color="primary lighten-1"
                  no-title
                  scrollable
                  locale="es-la"
                  @input="calendarioEmision = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col>
              <v-menu
                v-model="calendarioEnviadoMail"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    :value="dateToString(poliza.fecha_entrega_email)"
                    @click:clear="
                          $nextTick(() => (poliza.fecha_entrega_email = null))
                        "
                    clearable
                    label="Enviado x Mail"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="poliza.fecha_entrega_email"
                  clearable
                  color="primary lighten-1"
                  no-title
                  scrollable
                  locale="es-la"
                  @input="calendarioEnviadoMail = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col>
              <v-menu
                v-model="calendarioEntregaOriginal"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    :value="dateToString(poliza.fecha_entrega_original)"
                    @click:clear="
                          $nextTick(() => (poliza.fecha_entrega_original = null))
                        "
                    clearable
                    label="Entrega Original"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="poliza.fecha_entrega_original"
                  clearable
                  color="primary lighten-1"
                  no-title
                  scrollable
                  locale="es-la"
                  @input="calendarioEntregaOriginal = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="3">
              <v-text-field
                v-model="poliza.premio"
                label="Premio"
              ></v-text-field>
              <v-text-field
                v-model="poliza.comision"
                label="Comision"
              ></v-text-field>
            </v-col>
            <v-col cols="3">
              <v-text-field
                v-model="poliza.prima"
                label="Prima"
              ></v-text-field>
              <v-text-field
                v-model="poliza.descuento"
                label="Descuento"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-select
                v-model="poliza.forma_pago_id"
                :items="forma_pagos"
                item-value="id"
                item-text="nombre"
                label="Medio de Pago"
                :rules="[rules.required]"
              ></v-select>
              <v-row>
                <v-col class="pt-0">
                  <v-select
                    v-model="poliza.plan_pago"
                    :items="plan_pagos"
                    item-text="value"
                    item-value="value"
                    label="Plan de Pago"
                    :rules="[rules.required]"
                  ></v-select>
                </v-col>
                <v-col class="pt-0">
                  <v-text-field
                    v-model="poliza.cantidad_cuotas"
                    label="Cuotas"
                    :rules="[rules.required]"
                  ></v-text-field>
                </v-col>
              </v-row>
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
        @click="update"
        text
        color="green"
      >Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../../../helpers";

export default {
  data: () => ({
    calendarioEmision: false,
    calendarioEntregaOriginal: false,
    calendarioEnviadoMail: false,
    rules: {
      required: value => !!value || "Este campo obligatorio"
    },
    plan_pagos: [
      {
        value: "MENSUAL"
      },
      {
        value: "TRIMESTRAL"
      },
      {
        value: "SEMESTRAL"
      },
      {
        value: "ANUAL"
      },
      {
        value: "TOTAL"
      }
    ]
  }),
  computed: {
    ...mapState("poliza", ["poliza", "forma_pagos"])
  },
  mixins: [helpers],
  methods: {
    ...mapActions("poliza", ["updatePolizaRenovada"]),
    ...mapMutations("poliza", ["RESET_POLIZA", "DELETE_POLIZA_A_RENOVAR"]),
    ...mapMutations("modal", ["HIDE_MODAL", "SET_STEP"]),
    closeModal() {
      this.HIDE_MODAL(false);
      this.SET_STEP(1);
      this.RESET_POLIZA();
    },
    async update() {
      if (this.$refs.form.validate()) {
        if (this.poliza.numero) {
          const createResult = await this.updatePolizaRenovada(this.poliza);
          if (createResult) {
            this.DELETE_POLIZA_A_RENOVAR(this.poliza.id);
            this.poliza.riesgo_automotor.length > 0
              ? this.SET_STEP(2)
              : this.closeModal();
          }
        }
        this.SET_STEP(2);
      }
    }
  }
};
</script>

<style>
</style>