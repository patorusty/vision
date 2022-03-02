<template>
  <div>
    <v-card class="mt-0 mx-4 pa-3">
      <v-form ref="form">
        <v-container>
          <v-card-text>
            <v-row>
              <v-col :cols="3">
                <v-select
                  v-model="poliza.cliente_id"
                  :items="clientes"
                  item-value="id"
                  :item-text="nombreCompleto"
                  label="Asegurado"
                  :rules="[rules.required]"
                ></v-select>
                <v-select
                  v-model="poliza.tipo_riesgo_id"
                  :items="tipo_riesgos"
                  item-value="id"
                  item-text="nombre"
                  label="Tipo Riesgo"
                  :rules="[rules.required]"
                ></v-select>
                <v-select
                  v-model="poliza.compania_id"
                  @change="getCodigoProductores(poliza.compania_id)"
                  :items="companias"
                  item-value="id"
                  item-text="nombre"
                  label="Compania"
                  :rules="[rules.required]"
                ></v-select>
                <v-row>
                  <v-col class="pt-0">
                    <v-text-field
                      v-model="poliza.premio"
                      label="Premio"
                      :rules="[rules.required]"
                    ></v-text-field>
                    <v-text-field
                      v-model="poliza.comision"
                      label="Comision"
                      :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col class="pt-0">
                    <v-text-field
                      v-model="poliza.prima"
                      label="Prima"
                    ></v-text-field>
                    <v-text-field
                      v-model="poliza.descuento"
                      label="Descuento"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-col>
              <v-col :cols="3">
                <v-select
                  v-model="poliza.codigo_productor_id"
                  :items="codigo_productores"
                  :item-text="codigoProdText"
                  item-value="id"
                  label="Código Productor"
                  :rules="[rules.required]"
                ></v-select>
                <v-text-field
                  v-model="poliza.numero"
                  label="Poliza Nro"
                ></v-text-field>
                <v-select
                  v-model="poliza.forma_pago_id"
                  :items="forma_pagos"
                  item-value="id"
                  item-text="nombre"
                  label="Medio de Pago"
                  :rules="[rules.required]"
                ></v-select>
                <v-row>
                  <v-col>
                    <v-select
                      class="pt-0"
                      v-model="poliza.plan_pago"
                      :items="plan_pagos"
                      item-text="value"
                      item-value="value"
                      label="Plan de Pago"
                      :rules="[rules.required]"
                    ></v-select>
                  </v-col>
                  <v-col>
                    <v-text-field
                      class="pt-0"
                      v-model="poliza.cantidad_cuotas"
                      label="Cuotas"
                      :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-col>
              <v-col class="py-0">
                <v-row>
                  <v-col>
                    <v-select
                      v-model="poliza.tipo_vigencia_id"
                      :items="tipo_vigencias"
                      item-text="vigencia"
                      item-value="id"
                      label="Vigencia"
                      @change="sumarMes"
                    ></v-select>
                    <v-text-field
                      label="Desde"
                      v-mask="'##/##/####'"
                      :value="dateToString(poliza.vigencia_desde)"
                      clearable
                      @click:clear="$nextTick(() => (poliza.vigencia_desde = null))"
                      @change="updateVigencia"
                    />
                    <v-text-field
                      label="Hasta"
                      :value="dateToString(poliza.vigencia_hasta)"
                      v-mask="'##/##/####'"
                      clearable
                      @click:clear="$nextTick(() => (poliza.vigencia_hasta = null))"
                      @change="poliza.vigencia_hasta = stringToDate($event)"
                    />
                  </v-col>
                  <v-col>
                    <v-text-field
                      label="Fecha Solicitud"
                      :value="dateToString(poliza.fecha_solicitud)"
                      v-mask="'##/##/####'"
                      @change="poliza.fecha_solicitud = stringToDate($event)"
                      @click:clear="$nextTick(() => (poliza.fecha_solicitud = null))"
                      clearable
                    />
                    <v-text-field
                      label="Fecha Emision"
                      :value="dateToString(poliza.fecha_emision)"
                      v-mask="'##/##/####'"
                      @change="poliza.fecha_emision = stringToDate($event)"
                      @click:clear="$nextTick(() => (poliza.fecha_emision = null))"
                      clearable
                    />
                    <v-text-field
                      label="Fecha Recepcion"
                      :value="dateToString(poliza.fecha_recepcion)"
                      v-mask="'##/##/####'"
                      @change="poliza.fecha_recepcion = stringToDate($event)"
                      @click:clear="$nextTick(() => (poliza.fecha_recepcion = null))"
                      clearable
                    />
                  </v-col>
                  <v-col>
                    <v-text-field
                      label="Entrega Original"
                      :value="dateToString(poliza.fecha_entrega_original)"
                      v-mask="'##/##/####'"
                      @change="poliza.fecha_entrega_original = stringToDate($event)"
                      @click:clear="$nextTick(() => (poliza.fecha_entrega_original = null))"
                      clearable
                    />
                    <v-text-field
                      label="Enviado x Mail"
                      :value="dateToString(poliza.fecha_entrega_email)"
                      v-mask="'##/##/####'"
                      @change="poliza.fecha_entrega_email = stringToDate($event)"
                      @click:clear="$nextTick(() => (poliza.fecha_entrega_email = null))"
                      clearable
                    />
                    <v-text-field
                      label="Entrega x Correo"
                      :value="dateToString(poliza.fecha_entrega_correo)"
                      v-mask="'##/##/####'"
                      @change="poliza.fecha_entrega_correo = stringToDate($event)"
                      @click:clear="$nextTick(() => (poliza.fecha_entrega_correo = null))"
                      clearable
                    />

                    <!-- <v-menu
                      v-model="calendarioEntrgaCorreo"
                      :close-on-content-click="false"
                      transition="scale-transition"
                      offset-y
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          :value="dateToString(poliza.fecha_entrega_correo)"
                          @click:clear="
                          $nextTick(() => (poliza.fecha_entrega_correo = null))
                        "
                          clearable
                          label="Entrega x Correo"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="poliza.fecha_entrega_correo"
                        clearable
                        color="primary lighten-1"
                        no-title
                        scrollable
                        locale="es-la"
                        @input="calendarioEntrgaCorreo = false"
                      >
                      </v-date-picker>
                    </v-menu> -->
                  </v-col>
                </v-row>
                <v-row>
                  <v-col class="mt-0 py-0">
                    <v-textarea
                      v-model="poliza.detalle_medio_pago"
                      class="mt-0 pt-0"
                      label="Detalle"
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions class="mt-0 py-0 d-flex justify-end">
            <v-btn
              class="mb-2"
              color="red"
              text
              @click="volver"
            >Volver</v-btn>
            <v-btn
              class="mb-2"
              color="green"
              @click="update"
              text
              native-type="submit"
            >Guardar</v-btn>
          </v-card-actions>
        </v-container>
      </v-form>
    </v-card>
    <tabla-riesgo-automotor v-if="poliza.tipo_riesgo_id == 1" />
    <v-row>
      <v-col>
        <tabla-endosos />
      </v-col>
      <v-col>
        <tabla-siniestros />
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from "vuex";
import moment from "moment";
import { helpers } from "../../../helpers";
import TablaEndosos from "./Endosos/TablaEndosos";
import TablaSiniestros from "./Siniestros/TablaSiniestros";
import ModalCrearRiesgo from "./Riesgos/ModalCrearRiesgo";
import TablaRiesgoAutomotor from "./Riesgos/Automotor/TablaRiesgoAutomotor";
import ModalOtrosRiesgos from "./Riesgos/Otros Riesgos/ModalOtrosRiesgos";

export default {
  mixins: [helpers],
  components: {
    TablaEndosos,
    TablaSiniestros,
    ModalCrearRiesgo,
    ModalOtrosRiesgos,
    TablaRiesgoAutomotor
  },
  data: () => ({
    // calendarioDesde: false,
    // calendarioHasta: false,
    // calendarioSolicitud: false,
    // calendarioEmision: false,
    // calendarioEntregaOriginal: false,
    // calendarioEnviadoMail: false,
    // calendarioEntrgaCorreo: false,
    // calendarioRecepcion: false,
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
    ],
    rules: {
      required: value => !!value || "Este campo obligatorio"
    }
  }),
  computed: {
    ...mapState("cliente", ["clientes"]),
    ...mapState("compania", ["companias"]),
    ...mapState("codigo_productor", ["codigo_productores"]),
    ...mapState("poliza", [
      "poliza",
      "tipo_riesgos",
      "forma_pagos",
      "tipo_vigencias"
    ]),
    ...mapState("modal", ["modal", "modal_ra", "modal_or"])
  },
  methods: {
    ...mapActions("cliente", ["getClientes"]),
    ...mapActions("poliza", [
      "getPoliza",
      "getTipoRiesgos",
      "getFormaPagos",
      "getTipoVigencias",
      "updatePoliza"
    ]),
    ...mapActions("compania", ["getCompanias"]),
    ...mapActions("codigo_productor", ["getCodigoProductores"]),
    ...mapMutations("poliza", ["RESET_POLIZA"]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    volver() {
      this.$router.push({
        name: "Polizas"
      });
      // this.RESET_POLIZA();
    },
    async update() {
      if (this.$refs.form.validate()) {
        const createResult = await this.updatePoliza(this.poliza);
        if (createResult) {
          // this.$router.push({
          //   name: "Polizas"
          // });
          // this.RESET_POLIZA();
        }
      }
    }
  },
  created() {
    this.getPoliza(this.$route.params.id);
    this.getClientes();
    this.getCompanias();
    this.getTipoRiesgos();
    this.getFormaPagos();
    this.getTipoVigencias();
    this.sumarMes();
  },
  beforeDestroy() {
    this.RESET_POLIZA();
  }
};
</script>

<style>
</style>