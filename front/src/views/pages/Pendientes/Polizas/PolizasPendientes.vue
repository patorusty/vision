<template>
  <v-card
    class="mt-0 mx-4 pa-3"
    elevation="0"
  >
    <v-card-title>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="10"
      :items="polizas_pendientes"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.compania`]="{ item }">{{ item.compania.nombre }} <br />
        Cod.({{ item.codigo_productor.codigo_productor }})</template>
      <template v-slot:[`item.asegurado`]="{ item }">
        <router-link
          class="links"
          :to=" {name: 'Editar Cliente', params: {id: item.cliente.id}}"
          target="_blank"
        >{{ item.cliente.nombre }} {{ item.cliente.apellido }}
        </router-link>
      </template>
      <template v-slot:[`item.dominio`]="{ item }">
        <span v-if="item.tipo_riesgo_id == 1">{{dominio(item)}}</span>
        <v-icon v-else-if="item.tipo_riesgo_id == 2">mdi-account-group</v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 3"> mdi-fire </v-icon>
        <span v-else-if="item.tipo_riesgo_id == 4">
          <v-icon>mdi-alpha-r </v-icon>
          <v-icon> mdi-alpha-c </v-icon>
        </span>
        <v-icon v-else-if="item.tipo_riesgo_id == 5"> mdi-storefront </v-icon>

        <v-icon v-else-if="item.tipo_riesgo_id == 6"> mdi-medical-bag </v-icon>

        <v-icon v-else-if="item.tipo_riesgo_id == 7"> mdi-bicycle </v-icon>

        <v-icon v-else-if="item.tipo_riesgo_id == 8"> mdi-sail-boat </v-icon>

      </template>
      <template v-slot:[`item.desde`]="{ item }">{{ formatDate(item.vigencia_desde) }} <br />
        {{ formatDate(item.vigencia_hasta) }}</template>
      <template v-slot:[`item.envio`]="{ item }">{{ envio(item) }}</template>
      <template v-slot:[`item.pago`]="{ item }">{{ formaDePago(item) }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <router-link
          class="links"
          :to="{ name: 'Editar Poliza', params: { numero_solicitud: item.numero_solicitud } }"
        >
          <v-icon
            small
            class="mr-2"
            color="success"
          > mdi-pencil </v-icon>
        </router-link>
        <v-icon
          class="ml-2"
          small
          v-on:click.stop="openDeleteModal(item.id)"
          color="error"
        >
          mdi-close
        </v-icon>
      </template>
    </v-data-table>
    <v-dialog
      :retain-focus="false"
      max-width="30%"
      v-model="modalDelete"
    >
      <v-card class="pa-4">
        <v-card-text>
          <span>Esta seguro que desea eliminar esta Póliza?</span>
        </v-card-text>
        <v-card-actions class="py-0 pt-3 pr-6 d-flex justify-end">
          <v-btn
            dark
            color="red"
            @click="modalDelete = false"
          >Cancelar</v-btn>
          <v-btn
            class="ml-4"
            dark
            color="success"
            @click="deletePolicy"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { helpers } from "../../../../helpers";
export default {
  components: {},
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
      "polizas_pendientes",
      "loading",
      "tipo_riesgos",
      "estados"
    ]),
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
        { text: "Estado", value: "estado.nombre" },
        { text: "Envío", value: "envio" },
        { text: "Pago", value: "pago" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("poliza", [
      "getPolizasPendientes",
      "deletePoliza",
      "getTipoRiesgos",
      "getEstados"
    ]),
    ...mapActions("compania", ["getCompanias"]),
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deletePolicy() {
      this.deletePoliza(this.idSelected);
      this.modalDelete = false;
    },
    dominio(item) {
      if (item.tipo_riesgo_id == 1 && item.riesgo_automotor.length > 0) {
        return item.riesgo_automotor.length > 1
          ? "Autos"
          : item.riesgo_automotor[0].patente;
      }
      // else if (item.tipo_riesgo_id == 2 && item.otro_riesgo.length > 0) {
      //   return `<v-icon small class="mr-2" color="success"> mdi-pencil </v-icon>`;
      // }
    },
    envio(item) {
      if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo === null &&
        item.fecha_entrega_email === null
      ) {
        return "Recibida";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email === null
      ) {
        return "Enviada por correo";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo === null &&
        item.fecha_entrega_email !== null
      ) {
        return "Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo === null &&
        item.fecha_entrega_email === null
      ) {
        return "Entregada";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo === null &&
        item.fecha_entrega_email !== null
      ) {
        return "Entregada / Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email !== null
      ) {
        return "Entregada / Enviada por Correo / Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email === null
      ) {
        return "Entregada / Enviada por Correo";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email !== null
      ) {
        return "Enviada por Correo / Email";
      } else {
        return "No Recibida";
      }
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
    this.getPolizasPendientes();
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
