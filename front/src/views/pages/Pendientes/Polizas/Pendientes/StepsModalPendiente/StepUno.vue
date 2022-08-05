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
              <div
                v-if="!poliza.cliente.razon_social"
                class="text-subtitle-1"
              >
                {{"Cliente: " + poliza.cliente.nombre + " " +poliza.cliente.apellido}}
              </div>
              <div
                v-else
                class="text-subtitle-1"
              >
                {{"Cliente: " + poliza.cliente.razon_social}}
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
            <v-col class="py-0">
              <v-text-field
                label="Fecha Emision"
                :value="dateToString(poliza.fecha_emision)"
                v-mask="'##/##/####'"
                @change="poliza.fecha_emision = stringToDate($event)"
                @click:clear="$nextTick(() => (poliza.fecha_emision = null))"
                clearable
              />
            </v-col>
            <v-col class="py-0">
              <v-text-field
                label="Enviado x Mail"
                :value="dateToString(poliza.fecha_entrega_email)"
                v-mask="'##/##/####'"
                @change="poliza.fecha_entrega_email = stringToDate($event)"
                @click:clear="$nextTick(() => (poliza.fecha_entrega_email = null))"
                clearable
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col class="py-0">
              <v-text-field
                label="Fecha Recepcion"
                :value="dateToString(poliza.fecha_recepcion)"
                v-mask="'##/##/####'"
                @change="poliza.fecha_recepcion = stringToDate($event)"
                @click:clear="$nextTick(() => (poliza.fecha_recepcion = null))"
                clearable
              />

            </v-col>
            <v-col class="py-0">
              <v-text-field
                label="Entrega Original"
                :value="dateToString(poliza.fecha_entrega_original)"
                v-mask="'##/##/####'"
                @change="poliza.fecha_entrega_original = stringToDate($event)"
                @click:clear="$nextTick(() => (poliza.fecha_entrega_original = null))"
                clearable
              />
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
    ...mapActions("poliza", ["updatePolizaPendiente"]),
    ...mapMutations("poliza", ["RESET_POLIZA", "DELETE_POLIZA_PENDIENTE"]),
    ...mapMutations("modal", ["HIDE_MODAL", "SET_STEP"]),
    closeModal() {
      this.HIDE_MODAL(false);
      this.RESET_POLIZA();
      this.SET_STEP(1);
    },
    async update() {
      if (this.$refs.form.validate()) {
        if (this.poliza.numero) {
          const createResult = await this.updatePolizaPendiente(this.poliza);
          if (createResult) {
            this.DELETE_POLIZA_PENDIENTE(this.poliza.id);
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