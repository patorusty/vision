<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      <v-row>
        <v-col cols="3">
          <v-select
            v-model="tipo_riesgo_id"
            :items="riesgos"
            item-value="id"
            item-text="nombre"
            label="Tipo Riesgo"
            class="mr-5"
          ></v-select>
        </v-col>
        <v-col cols="7">
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
            class="mr-3 ml-3 mb-5"
          ></v-text-field>
        </v-col>
        <v-col cols="2">
          <v-btn
            color="primary"
            to="/polizas/automotor/create"
            dark
            class="ml-5"
          >Crear</v-btn>
        </v-col>
      </v-row>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="10"
      :items="tableData"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.compania`]="{ item }">{{ item.compania.nombre }} <br />
        Cod.({{ item.codigo_productor.codigo_productor }})</template>
      <template v-slot:[`item.asegurado`]="{ item }">
        <router-link
          class="links"
          :to=" {name: 'Editar Cliente', params: {id: item.id}}"
          target="_blank"
        >{{ item.clientes.nombre }} {{ item.clientes.apellido }}
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
import { helpers } from "../../../helpers";
export default {
  components: {},
  mixins: [helpers],
  data: () => ({
    tipo_riesgo_id: 1,
    search: "",
    idSelected: null,
    modalDelete: false,
    headers: [
      { text: "Poliza N°", value: "numero" },
      { text: "Compania", value: "compania" },
      { text: "Asegurado", value: "asegurado" },
      { text: "Dominio", value: "dominio" },
      { text: "Vigencia", value: "tipo_vigencias.vigencia" },
      { text: "Desde / Hasta", value: "desde" },
      { text: "Estado", value: "estado.nombre" },
      { text: "Envío", value: "envio" },
      { text: "Pago", value: "pago" },
      { text: "Actions", value: "actions", sortable: false, align: "right" }
    ]
  }),
  computed: {
    ...mapState("poliza", ["polizas", "loading", "tipo_riesgos"]),
    tableData() {
      return this.polizas.filter(item =>
        this.tipo_riesgo_id != 0
          ? item.tipo_riesgo_id === this.tipo_riesgo_id
          : item.tipo_riesgo_id != 0
      );
    },
    riesgos() {
      var r = [...this.tipo_riesgos, { id: 0, nombre: "TODOS" }];
      return r;
    }
  },
  methods: {
    ...mapActions("poliza", ["getPolizas", "deletePoliza", "getTipoRiesgos"]),
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
      } else if (item.tipo_riesgo_id == 2 && item.otro_riesgo.length > 0) {
        return `<v-icon small class="mr-2" color="success"> mdi-pencil </v-icon>`;
      }
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
    this.getPolizas();
    this.getTipoRiesgos();
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
