<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-text>
      <v-row>
        <v-col
          :cols="3"
          class="d-flex flex-column justify-space-between"
        >
          <v-select
            v-model="poliza.cliente_id"
            :items="clientes"
            item-value="id"
            :item-text="nombreCompleto"
            label="Asegurado"
          ></v-select>
          <v-select
            v-model="poliza.tipo_riesgo_id"
            :items="tipo_riesgos"
            item-value="id"
            item-text="nombre"
            label="Tipo Riesgo"
          ></v-select>
          <v-select
            v-model="poliza.compania_id"
            :items="companias"
            item-value="id"
            item-text="nombre"
            label="Compania"
          ></v-select>
          <v-row>
            <v-col class="py-0">
              <v-text-field label="Premio"></v-text-field>
              <v-text-field label="Comision"></v-text-field>
            </v-col>
            <v-col class="py-0">
              <v-text-field label="Prima"></v-text-field>
              <v-text-field label="Descuento"></v-text-field>
            </v-col>
          </v-row>
        </v-col>
        <v-col
          :cols="3"
          class="d-flex flex-column justify-space-between"
        >
          <v-select label="Código Productor"></v-select>
          <v-text-field label="Poliza Nro"></v-text-field>
          <v-row>
            <v-col class="py-0">
              <v-text-field label="Renueva Poliza Nro"></v-text-field>
            </v-col>
            <v-col class="py-0">
              <v-text-field label="Nro de Solicitud"></v-text-field>
            </v-col>
          </v-row>
          <v-select
            v-model="poliza.forma_pago_id"
            :items="forma_pagos"
            item-value="id"
            item-text="nombre"
            label="Medio de Pago"
          ></v-select>
          <v-row>
            <v-col class="py-0">
              <v-select
                v-model="poliza.plan_pago"
                :items="plan_pagos"
                item-text="value"
                item-value="value"
                label="Plan de Pago"
              ></v-select>
            </v-col>
            <v-col
              class="py-0"
              :cols="6"
            >
              <v-text-field label="Nro de Solicitud"></v-text-field>
            </v-col>
          </v-row>
        </v-col>
        <v-col class="d-flex flex-column justify-space-between">
          <v-row>
            <v-col class="py-0 d-flex flex-column justify-space-between">
              <v-select label="Vigencia"></v-select>
              <v-menu
                v-model="calendarioDesde"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    clearable
                    label="Desde"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
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
                    clearable
                    label="Hasta"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
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
                    clearable
                    label="Solicitud"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
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
                    clearable
                    label="Emision"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
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
                    clearable
                    label="Recepcion"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
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
                    clearable
                    label="Entrega Original"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
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
                    clearable
                    label="Enviado x Mail"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
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
                    clearable
                    label="Entrega x Correo"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
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
            <v-col class="py-0">
              <v-textarea label="Detalle"></v-textarea>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { helpers } from "../../../helpers";
export default {
  mixins: [helpers],
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
    ]
  }),
  computed: {
    ...mapState("cliente", ["clientes"]),
    ...mapState("compania", ["companias"]),
    ...mapState("poliza", ["poliza", "tipo_riesgos", "forma_pagos"])
  },
  methods: {
    ...mapActions("cliente", ["getClientes"]),
    ...mapActions("poliza", ["getTipoRiesgos", "getFormaPagos"]),
    ...mapActions("compania", ["getCompanias"])
  },
  created() {
    this.getClientes();
    this.getCompanias();
    this.getTipoRiesgos();
    this.getFormaPagos();
  }
};
</script>

<style>
</style>