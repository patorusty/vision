<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      Detalle de Riesgo
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
        @click="SHOW_MODAL(false);"
        dark
      >Crear</v-btn>
      <v-dialog
        @click:outside="HIDE_MODAL(false)"
        :value="modal"
        max-width="40%"
      >
        <modal-riesgo-automotor />
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="riesgo_automotores"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          @click="editCodigoOrganizador(item.id)"
          class="mr-2"
          color="success"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          class="ml-2"
          small
          @click="openDeleteModal(item.id)"
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
          <span>Esta seguro que desea eliminar este Riesgo?</span>
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
            @click="deleteRiesgoAut"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalRiesgoAutomotor from "./ModalRiesgoAutomotor";
import { helpers } from "../../../../../helpers";

export default {
  components: {
    ModalRiesgoAutomotor
  },
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: "",
    modalDelete: false
  }),
  computed: {
    ...mapState("modal", ["modal"]),
    headers() {
      return [
        { text: "Tipo", value: "riesgo_automotor.tipo" },
        { text: "Marca", value: "riesgo_automotor.marca" },
        { text: "Patente", value: "riesgo_automotor.patente" },
        {
          text: "Suma Asegurada",
          value: "riesgo_automotor.valor_total"
        },
        { text: "Cobertura", value: "riesgo_automotor.cobertura.nombre" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("riesgo_automotor", [
      "getRiesgoAutomotor",
      "deleteRiesgoAutomotor"
    ]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    editRiesgoAutomotor(id) {
      this.getRiesgoAutomotor(id);
      this.SHOW_MODAL(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteRiesgoAut() {
      this.deleteRiesgoAutomotor(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    }
  },
  created() {}
};
</script>

<style>
</style>