<template>
  <v-card class="mt-0 mx-4 pa-3" elevation="0">
    <v-card-title>
    </v-card-title>
    <v-data-table class="pa-2" :headers="headers" :items-per-page="10" :items="polizas_a_renovar" multi-sort
      :loading="loading">
      <template v-slot:[`item.compania`]="{ item }">{{ item.compania.nombre }} <br />
        Cod.({{ item.codigo_productor.codigo_productor }})</template>
      <template v-slot:[`item.asegurado`]="{ item }">
        <router-link class="links" :to="{ name: 'Editar Cliente', params: { id: item.cliente.id } }" target="_blank">{{
          nombreCompleto(item.cliente) }}
        </router-link>
      </template>
      <template v-slot:[`item.dominio`]="{ item }">
        <span v-if="item.tipo_riesgo_id == 1">{{ dominio(item) }}</span>
        <v-icon v-else-if="item.tipo_riesgo_id == 2">mdi-home</v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 3"> mdi-fire </v-icon>
        <span v-else-if="item.tipo_riesgo_id == 4">
          <v-icon>mdi-alpha-r </v-icon>
          <v-icon> mdi-alpha-c </v-icon>
        </span>
        <span v-else-if="item.tipo_riesgo_id == 9">
          <v-icon>mdi-alpha-s </v-icon>
          <v-icon> mdi-alpha-t </v-icon>
        </span>
        <span v-else-if="item.tipo_riesgo_id == 10">
          <v-icon>mdi-alpha-c </v-icon>
          <v-icon> mdi-alpha-a </v-icon>
          <v-icon> mdi-alpha-u </v-icon>
        </span>
        <v-icon v-else-if="item.tipo_riesgo_id == 5"> mdi-storefront </v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 6"> mdi-medical-bag </v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 11"> mdi-truck </v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 12"> mdi-account-group </v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 13"> mdi-account-multiple-check </v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 14"> mdi-account-lock-outline </v-icon>
        <span v-else-if="item.tipo_riesgo_id == 7">
          <v-icon v-if="item.otro_riesgo && item.otro_riesgo.tipo == 'Monopatin Electrico'"> mdi-scooter </v-icon>
          <v-icon v-else> mdi-bicycle </v-icon>
        </span>
        <v-icon v-else-if="item.tipo_riesgo_id == 8"> mdi-sail-boat </v-icon>
      </template>
      <template v-slot:[`item.desde`]="{ item }">{{ dateToString(item.vigencia_desde) }} <br />
        {{ dateToString(item.vigencia_hasta) }}</template>
      <template v-slot:[`item.pago`]="{ item }">{{ formaDePago(item) }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small class="mr-2" color="success" v-on:click.stop="openModalPendiente(item)"> mdi-pencil </v-icon>
        <!-- <v-icon
          class="ml-2"
          small
          v-on:click.stop="openDeleteModal(item.id)"
          color="error"
        >
          mdi-close
        </v-icon> -->
      </template>
    </v-data-table>
    <v-dialog :retain-focus="false" @click:outside="closeModalPend" @keydown.esc="closeModalPend" :value="modal"
      max-width="40%">
      <modal-polizas-a-renovar />
    </v-dialog>
    <v-dialog :retain-focus="false" max-width="30%" v-model="modalDelete">
      <v-card class="pa-4">
        <v-card-text>
          <span>Esta seguro que desea eliminar esta Póliza?</span>
        </v-card-text>
        <v-card-actions class="py-0 pt-3 pr-6 d-flex justify-end">
          <v-btn dark color="red" @click="modalDelete = false">Cancelar</v-btn>
          <v-btn class="ml-4" dark color="success" @click="deletePolicy">Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../../helpers";
import ModalPolizasARenovar from "./ModalPolizasARenovar.vue";
export default {
  components: { ModalPolizasARenovar },
  mixins: [helpers],
  data: () => ({
    tipo_riesgo_id: 1,
    poliza: "",
    patente: "",
    compania_id: 0,
    cliente: "",
    idSelected: null,
    modalDelete: false,
    filtroEstado: []
  }),
  computed: {
    ...mapState("poliza", [
      "polizas_a_renovar",
      "loading",
      "tipo_riesgos",
      "estados"
    ]),
    ...mapState("modal", ["modal"]),
    ...mapState("compania", ["companias"]),
    headers() {
      return [
        {
          text: "Poliza N°",
          value: "numero",
          filterable: true
        },
        {
          text: "Compania",
          value: "compania",
          filterable: true
        },
        { text: "Asegurado", value: "asegurado" },
        { text: "Dominio", value: "dominio" },
        { text: "Vigencia", value: "tipo_vigencias.vigencia" },
        { text: "Desde / Hasta", value: "desde" },
        { text: "Pago", value: "pago" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("poliza", [
      "getPolizasARenovar",
      "deletePoliza",
      "getTipoRiesgos",
      "getEstados"
    ]),
    ...mapActions("compania", ["getCompanias"]),
    ...mapActions("cobertura", ["getCoberturasActivas"]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL", "SET_STEP"]),
    ...mapMutations("poliza", ["SET_POLIZA", "RESET_POLIZA"]),
    ...mapMutations("riesgo", ["SET_RIESGO_AUTOMOTORES"]),
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deletePolicy() {
      this.deletePoliza(this.idSelected);
      this.modalDelete = false;
    },
    openModalPendiente(item) {
      this.SET_POLIZA(item);
      this.getCoberturasActivas(item.compania_id);
      this.SET_RIESGO_AUTOMOTORES(item.riesgo_automotor);
      this.SHOW_MODAL(true);
    },
    closeModalPend() {
      this.HIDE_MODAL(false);
      this.SET_STEP(1);
      this.RESET_POLIZA();
    },
    dominio(item) {
      if (item.tipo_riesgo_id == 1 && item.riesgo_automotor.length > 0) {
        return item.riesgo_automotor.length > 1
          ? "Flota"
          : item.riesgo_automotor[0].patente;
      }
      // else if (item.tipo_riesgo_id == 2 && item.otro_riesgo.length > 0) {
      //   return `<v-icon small class="mr-2" color="success"> mdi-pencil </v-icon>`;
      // }
    },
    formaDePago(item) {
      if (item.forma_pago_id == 1) {
        return "TC";
      } else if (item.forma_pago_id == 2) {
        return "DC";
      } else {
        return "PF / RP";
      }
    }
  },
  created() {
    this.getPolizasARenovar();
    this.getCompanias();
    this.getTipoRiesgos();
    this.getEstados();
  }
};
</script>

<style>
.links {
  text-decoration: none;
}

.titulo-polizas {
  display: flex;
  justify-content: space-evenly;
}
</style>
