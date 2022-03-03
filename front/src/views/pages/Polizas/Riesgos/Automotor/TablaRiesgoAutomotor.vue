<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      Detalle de Riesgo
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        @click="create"
        dark
      >Crear</v-btn>
      <v-dialog
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        @click:outside="HIDE_MODAL_RA(false)"
        @keydown.esc="close"
        :value="modal_ra"
        max-width="80%"
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
    >
      <template v-slot:[`item.suma`]="{ item }">
        <div>{{ suma(item)}}</div>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          @click="editRiesgoAutomotor(item)"
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
import ModalRiesgoAutomotor from "./ModalRiesgoAutomotor/ModalRiesgoAutomotor";
import { helpers } from "../../../../../helpers";
import { bus } from "../../../../../main";

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
    ...mapState("modal", ["modal_ra"]),
    ...mapState("riesgo", ["riesgo_automotores"]),
    ...mapState("poliza", ["poliza"]),
    headers() {
      return [
        // { text: "Tipo", value: "riesgo_automotor.tipo" },
        { text: "Marca", value: "marca.nombre" },
        { text: "Version", value: "version.nombre" },
        { text: "Patente", value: "patente" },
        {
          text: "Suma Asegurada",
          value: "suma"
        },
        { text: "Cobertura", value: "cobertura.nombre" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("riesgo", [
      "getRiesgoAutomotor",
      "deleteRiesgoAutomotor",
      "getTipoVehiculos",
      "getTipoCarrocerias"
    ]),
    ...mapActions("anio", ["getAnios"]),
    ...mapActions("marca", ["getMarcas"]),
    ...mapActions("modelo", ["getModelos"]),
    ...mapActions("version", ["getVersiones"]),
    ...mapMutations("modal", ["SHOW_MODAL_RA", "HIDE_MODAL_RA"]),
    ...mapMutations("riesgo", [
      "SET_RIESGO_AUTOMOTOR",
      "SET_RIESGO_AUTOMOTORES",
      "RESET_RIESGO_AUTOMOTOR"
    ]),
    editRiesgoAutomotor(item) {
      this.SET_RIESGO_AUTOMOTOR(item);
      this.SHOW_MODAL_RA(true);
    },
    create() {
      this.RESET_RIESGO_AUTOMOTOR();
      this.SHOW_MODAL_RA(false);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteRiesgoAut() {
      this.deleteRiesgoAutomotor(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    },
    close() {
      bus.$emit("closeModalRA", true);
    }
  },
  created() {
    this.getAnios();
    this.getMarcas();
    this.getModelos();
    this.getVersiones();
    this.getTipoVehiculos();
    this.getTipoCarrocerias();
  }
};
</script>

<style>
</style>