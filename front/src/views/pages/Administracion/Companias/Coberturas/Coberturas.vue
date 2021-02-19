<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      Coberturas
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
        @click="SHOW_MODAL3(false)"
        dark
      >Crear</v-btn>
      <v-dialog
        @click:outside="HIDE_MODAL3(false)"
        :value="modal3"
        max-width="40%"
      >
        <modal-coberturas />
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="coberturas"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template
        slot="item.todo_riesgo"
        slot-scope="props"
      >{{
        textoTR(props.item.todo_riesgo)
      }}</template>
      <template
        slot="item.activo"
        slot-scope="props"
      >{{
        textoActivo(props.item.activo)
      }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          @click="editCoberturas(item.id)"
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
          <span>Esta seguro que desea eliminar esta Cobertura?</span>
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
            @click="deleteCob"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalCoberturas from "./ModalCoberturas";
import { helpers } from "../../../../../helpers";

export default {
  components: {
    ModalCoberturas
  },
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: "",
    modalDelete: false
  }),
  computed: {
    ...mapState("compania", ["loading"]),
    ...mapState("cobertura", ["coberturas"]),
    ...mapState("modal", ["modal3", "edicion3"]),
    headers() {
      return [
        { text: "Nombre", value: "nombre" },
        { text: "Antiguedad", value: "antiguedad" },
        { text: "Todo Riesgo", value: "todo_riesgo" },
        {
          text: "Franquicia",
          value: "franquicia"
        },
        { text: "Ajuste", value: "ajuste" },
        { text: "Activa", value: "activo" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("cobertura", ["getCobertura", "deleteCobertura"]),
    ...mapMutations("modal", ["SHOW_MODAL3", "HIDE_MODAL3"]),
    editCoberturas(id) {
      this.getCobertura(id);
      this.SHOW_MODAL3(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteCob() {
      this.deleteCobertura(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    },
    textoTR(nro) {
      return nro === 1 || nro === true ? "SI" : "NO";
    }
  }
};
</script>

<style>
</style>