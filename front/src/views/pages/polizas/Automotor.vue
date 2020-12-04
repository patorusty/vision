<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
        v-uppercase
      ></v-text-field>
      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        @click="SHOW_MODAL(false)"
        dark
      >Crear</v-btn>
      <v-dialog
        @click:outside="HIDE_MODAL(false)"
        :value="modal"
        max-width="70%"
      >
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="10"
      :items="polizas"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.compania`]="{ item }">{{ item.companias.nombre }} <br />
        Cod.({{ item.codigo_productor.codigo_productor }})</template>
      <template v-slot:[`item.asegurado`]="{ item }">{{ item.clientes.nombre }} {{ item.clientes.apellido }}</template>
      <template v-slot:[`item.patente`]="{ item }">{{  patente(item)  }}</template>
      <template v-slot:[`item.desde`]="{ item }">{{ formatDate(item.vigencia_desde) }} <br />
        {{ formatDate(item.vigencia_hasta) }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <router-link
          class="links"
          :to="{ name: 'Automotor' }"
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
          <span>Esta seguro que desea eliminar esta Compania?</span>
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
            @click="deleteCompany"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../helpers";
export default {
  components: {},
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: null,
    modalDelete: false,
    headers: [
      { text: "Poliza N°", value: "numero" },
      { text: "Compania", value: "compania" },
      { text: "Asegurado", value: "asegurado" },
      { text: "Patente", value: "patente" },
      { text: "Vigencia", value: "tipo_vigencias.vigencia" },
      { text: "Desde / Hasta", value: "desde" },
      { text: "Estado", value: "estado_polizas.nombre" },
      { text: "Envío", value: "envio" },
      { text: "Pago", value: "pago" },
      { text: "Actions", value: "actions", sortable: false, align: "right" }
    ]
  }),
  computed: {
    ...mapState("poliza", ["polizas", "loading"]),
    ...mapState("modal", ["modal"])
  },
  methods: {
    ...mapActions("poliza", ["getPolizas", "deletePoliza"]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteCompany() {
      this.deletePoliza(this.idSelected);
      this.modalDelete = false;
    },
    patente(item) {
      if (item.riesgo_automotor.length > 0) {
        return item.riesgo_automotor.length > 1
          ? "Autos"
          : item.riesgo_automotor[0].patente;
      } else {
        return "sin riesgo";
      }
    }
  },
  created() {
    this.getPolizas();
  }
};
</script>

<style>
.links {
  text-decoration: none;
}
</style>
