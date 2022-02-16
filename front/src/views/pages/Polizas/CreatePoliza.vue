<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-form ref="form">
      <v-container>
        <v-card-text>
          <v-row>
            <v-col :cols="3">
              <v-autocomplete
                v-model="poliza.cliente_id"
                :items="clientes"
                item-value="id"
                :item-text="nombreCompleto"
                label="Asegurado"
                :rules="[rules.required]"
              >
                <template v-slot:append-outer>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-icon
                        class="mr-1 icon-action"
                        v-on="on"
                        @click="createCliente"
                      >mdi-plus-thick</v-icon>
                      <v-dialog
                        fullscreen
                        hide-overlay
                        transition="dialog-bottom-transition"
                        @click:outside="HIDE_MODAL(false)"
                        :value="modal"
                        max-width="80%"
                      >
                        <modal-cliente />
                      </v-dialog>
                    </template>
                    <span>Agregar</span>
                  </v-tooltip>
                </template>
              </v-autocomplete>
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
                @change="getCodigoProductoresActivos(poliza.compania_id)"
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
              <!-- <v-text-field
                disabled
                v-model="poliza.numero_solicitud"
                label="Nro de Solicitud"
              ></v-text-field> -->
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
            <v-col>
              <v-row>
                <v-col class="py-0 d-flex flex-column justify-space-between">
                  <v-select
                    v-model="poliza.tipo_vigencia_id"
                    :items="tipo_vigencias"
                    item-text="vigencia"
                    item-value="id"
                    label="Vigencia"
                    @change="sumarMes"
                  ></v-select>
                  <v-menu
                    v-model="calendarioDesde"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="formatDate(poliza.vigencia_desde)"
                        @click:clear="
                          $nextTick(() => (poliza.vigencia_desde = null))
                        "
                        clearable
                        label="Desde"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      @change="sumarMes"
                      v-model="poliza.vigencia_desde"
                      clearable
                      color="primary lighten-1"
                      no-title
                      scrollable
                      locale="es-la"
                      @input="calendarioDesde = false"
                    >
                    </v-date-picker>
                  </v-menu>
                  <v-menu
                    v-model="calendarioHasta"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="formatDate(poliza.vigencia_hasta)"
                        @click:clear="
                          $nextTick(() => (poliza.vigencia_hasta = null))
                        "
                        clearable
                        label="Hasta"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="poliza.vigencia_hasta"
                      clearable
                      color="primary lighten-1"
                      no-title
                      scrollable
                      locale="es-la"
                      @input="calendarioHasta = false"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col class="py-0 d-flex flex-column justify-space-between">
                  <v-menu
                    v-model="calendarioSolicitud"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="formatDate(poliza.fecha_solicitud)"
                        @click:clear="
                          $nextTick(() => (poliza.fecha_solicitud = null))
                        "
                        clearable
                        label="Solicitud"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="poliza.fecha_solicitud"
                      clearable
                      color="primary lighten-1"
                      no-title
                      scrollable
                      locale="es-la"
                      @input="calendarioSolicitud = false"
                    >
                    </v-date-picker>
                  </v-menu>
                  <v-menu
                    v-model="calendarioEmision"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="formatDate(poliza.fecha_emision)"
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
                  <v-menu
                    v-model="calendarioRecepcion"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="formatDate(poliza.fecha_recepcion)"
                        @click:clear="
                          $nextTick(() => (poliza.fecha_recepcion = null))
                        "
                        clearable
                        label="Recepcion"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="poliza.fecha_recepcion"
                      clearable
                      color="primary lighten-1"
                      no-title
                      scrollable
                      locale="es-la"
                      @input="calendarioRecepcion = false"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col class="py-0 d-flex flex-column justify-space-between">
                  <v-menu
                    v-model="calendarioEntregaOriginal"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="formatDate(poliza.fecha_entrega_original)"
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
                  <v-menu
                    v-model="calendarioEnviadoMail"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="formatDate(poliza.fecha_entrega_email)"
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
                  <v-menu
                    v-model="calendarioEntrgaCorreo"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        :value="formatDate(poliza.fecha_entrega_correo)"
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
                  </v-menu>
                </v-col>
              </v-row>
              <v-row>
                <v-col class=" mt-0 py-0">
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
        <v-card-actions class="d-flex justify-end">
          <v-btn
            class="mb-2"
            color="red"
            text
            @click="volver"
          >Volver</v-btn>
          <v-btn
            class="mb-2"
            color="green"
            @click="create"
            text
            native-type="submit"
          >Crear</v-btn>
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
    calendarioDesde: false,
    calendarioHasta: false,
    calendarioSolicitud: false,
    calendarioEmision: false,
    calendarioEntregaOriginal: false,
    calendarioEnviadoMail: false,
    calendarioEntrgaCorreo: false,
    calendarioRecepcion: false,
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
    ...mapMutations("modal", ["SHOW_MODAL"]),
    sumarMes() {
      this.poliza.vigencia_hasta = moment(this.poliza.vigencia_desde).add(
        this.poliza.tipo_vigencia_id,
        "M"
      );
    },
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
          this.$router.push({
            name: "Editar Poliza",
            params: { id: this.poliza.id }
          });
          this.RESET_POLIZA();
        }
      }
    }
  },
  created() {
    this.getClientes();
    this.getCompaniasActivas();
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