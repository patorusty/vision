<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-form ref="form">
      <v-container>
        <v-card-text>
          <v-row>
            <v-col :cols="3">
              <v-autocomplete v-model="poliza.cliente_id" :items="clientes" item-value="id" :item-text="nombreCompleto"
                label="Asegurado" :rules="[rules.required]">
                <template v-slot:append-outer>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-icon class="mr-1 icon-action" v-on="on" @click="createCliente">mdi-plus-thick</v-icon>
                      <v-dialog fullscreen hide-overlay transition="dialog-bottom-transition"
                        @click:outside="HIDE_MODAL(false)" :value="modal" max-width="80%" :retain-focus="false">
                        <modal-cliente />
                      </v-dialog>
                    </template>
                    <span>Agregar</span>
                  </v-tooltip>
                </template>
              </v-autocomplete>
              <v-select v-model="poliza.tipo_riesgo_id" :items="tipo_riesgos" item-value="id" item-text="nombre"
                label="Tipo Riesgo" :rules="[rules.required]"></v-select>
              <v-select v-model="poliza.compania_id" @change="getCodigoProductoresActivos(poliza.compania_id)"
                :items="companias" item-value="id" item-text="nombre" label="Compania"
                :rules="[rules.required]"></v-select>
              <v-row>
                <v-col class="pt-0">
                  <v-text-field v-model="poliza.premio" label="Premio" :rules="[rules.required]"></v-text-field>
                  <v-text-field v-model="poliza.comision" label="Comision" :rules="[rules.required]"></v-text-field>
                </v-col>
                <v-col class="pt-0">
                  <v-text-field v-model="poliza.prima" label="Prima"></v-text-field>
                  <v-text-field v-model="poliza.descuento" label="Descuento"></v-text-field>
                </v-col>
              </v-row>
            </v-col>
            <v-col :cols="3">
              <v-select v-model="poliza.codigo_productor_id" :items="codigo_productores" :item-text="codigoProdText"
                item-value="id" label="Código Productor" :rules="[rules.required]"></v-select>
              <v-text-field class="input-numero" v-model="poliza.numero" label="Poliza Nro"></v-text-field>
              <v-select v-model="poliza.forma_pago_id" :items="forma_pagos" item-value="id" item-text="nombre"
                label="Medio de Pago" :rules="[rules.required]"></v-select>
              <v-row>
                <v-col>
                  <v-select class="pt-0" v-model="poliza.plan_pago" :items="plan_pagos" item-text="value"
                    item-value="value" label="Plan de Pago" :rules="[rules.required]"></v-select>
                </v-col>
                <v-col>
                  <v-text-field class="pt-0" v-model="poliza.cantidad_cuotas" label="Cuotas"
                    :rules="[rules.required]"></v-text-field>
                </v-col>
              </v-row>
            </v-col>
            <v-col>
              <v-row>
                <v-col>
                  <v-select v-model="poliza.tipo_vigencia_id" :items="tipo_vigencias" item-text="vigencia" item-value="id"
                    label="Vigencia" @change="sumarMes"></v-select>
                  <v-text-field label="Desde" v-mask="'##/##/####'" :value="dateToString(poliza.vigencia_desde)" clearable
                    @click:clear="$nextTick(() => (poliza.vigencia_desde = null))" @change="updateVigencia" />
                  <v-text-field label="Hasta" :value="dateToString(poliza.vigencia_hasta)" v-mask="'##/##/####'" clearable
                    @click:clear="$nextTick(() => (poliza.vigencia_hasta = null))"
                    @change="poliza.vigencia_hasta = stringToDate($event)" />
                </v-col>
                <v-col>
                  <v-text-field label="Fecha Solicitud" :value="dateToString(poliza.fecha_solicitud)"
                    v-mask="'##/##/####'" @change="poliza.fecha_solicitud = stringToDate($event)"
                    @click:clear="$nextTick(() => (poliza.fecha_solicitud = null))" clearable />
                  <v-text-field label="Fecha Emision" :value="dateToString(poliza.fecha_emision)" v-mask="'##/##/####'"
                    @change="poliza.fecha_emision = stringToDate($event)"
                    @click:clear="$nextTick(() => (poliza.fecha_emision = null))" clearable />
                  <v-text-field label="Fecha Recepcion" :value="dateToString(poliza.fecha_recepcion)"
                    v-mask="'##/##/####'" @change="poliza.fecha_recepcion = stringToDate($event)"
                    @click:clear="$nextTick(() => (poliza.fecha_recepcion = null))" clearable />
                </v-col>
                <v-col>
                  <v-text-field label="Entrega Original" :value="dateToString(poliza.fecha_entrega_original)"
                    v-mask="'##/##/####'" @change="poliza.fecha_entrega_original = stringToDate($event)"
                    @click:clear="$nextTick(() => (poliza.fecha_entrega_original = null))" clearable />
                  <v-text-field label="Enviado x Mail" :value="dateToString(poliza.fecha_entrega_email)"
                    v-mask="'##/##/####'" @change="poliza.fecha_entrega_email = stringToDate($event)"
                    @click:clear="$nextTick(() => (poliza.fecha_entrega_email = null))" clearable />
                </v-col>
              </v-row>
              <v-row>
                <v-col class=" mt-0 py-0">
                  <v-textarea v-model="poliza.detalle_medio_pago" class="mt-0 pt-0" label="Detalle"></v-textarea>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions class="d-flex justify-end">
          <v-btn class="mb-2" color="red" text @click="volver">Volver</v-btn>
          <v-btn class="mb-2" color="green" @click="create" text native-type="submit">Crear</v-btn>
        </v-card-actions>
      </v-container>
    </v-form>
  </v-card>
</template>

<script>
import { mapActions, mapState, mapMutations } from "vuex";
import moment from "moment";
import ModalCliente from "../Clientes/ModalCliente.vue";
import { helpers } from "../../../helpers";
export default {
  mixins: [helpers],
  components: { ModalCliente },
  data: () => ({
    plan_pagos: [
      {
        value: "MENSUAL"
      },
      {
        value: "TRIMESTRAL"
      },
      {
        value: "CUATRIMESTRAL"
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
    ...mapState("modal", ["modal"]),
    ...mapState("poliza", [
      "poliza",
      "tipo_riesgos",
      "forma_pagos",
      "tipo_vigencias"
    ])
  },
  methods: {
    ...mapActions("cliente", ["getClientes"]),
    ...mapActions("poliza", [
      "getTipoRiesgos",
      "getFormaPagos",
      "getTipoVigencias",
      "createPoliza"
    ]),
    ...mapActions("compania", ["getCompaniasActivas"]),
    ...mapActions("codigo_productor", ["getCodigoProductoresActivos"]),
    ...mapMutations("poliza", ["RESET_POLIZA"]),
    ...mapMutations("cliente", ["RESET_CLIENTE"]),
    ...mapMutations("endoso", ["RESET_ENDOSOS"]),
    ...mapMutations("siniestro", ["RESET_SINIESTROS"]),
    ...mapMutations("riesgo", [
      "RESET_RIESGOS_AUTOMOTORES",
      "RESET_OTRO_RIESGO"
    ]),
    ...mapMutations("modal", ["SHOW_MODAL"]),
    volver() {
      this.$router.push({
        name: "Polizas"
      });
    },
    createCliente() {
      this.RESET_CLIENTE();
      this.SHOW_MODAL(false);
    },
    async create() {
      if (this.$refs.form.validate()) {
        const createResult = await this.createPoliza(this.poliza);
        if (createResult) {
          this.RESET_CLIENTE();
          this.RESET_ENDOSOS();
          this.RESET_RIESGOS_AUTOMOTORES();
          this.RESET_OTRO_RIESGO();
          this.RESET_SINIESTROS();
          this.$router.push({
            name: "Editar Poliza",
            params: { id: this.poliza.id }
          });
        }
      }
    }
  },
  created() {
    this.RESET_POLIZA();
    this.getClientes();
    this.getCompaniasActivas();
    this.getTipoRiesgos();
    this.getFormaPagos();
    this.getTipoVigencias();
    this.sumarMes();
  }
};
</script>

<style>
.input-numero .v-text-field__slot input {
  font-weight: bold !important;
  color: #e91e63 !important;
}
</style>