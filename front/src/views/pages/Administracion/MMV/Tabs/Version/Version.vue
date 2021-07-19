<template>
  <v-card
    class="mt-0 mx-4 pa-3"
    elevation=0
  >
    <v-card-title class="d-flex align-top">
      <v-autocomplete
        class="mt-6"
        :value.sync="marca_id"
        :items="marcas"
        item-text="nombre"
        item-value="id"
        label="Marca"
        @input="updateMarcaId"
      ></v-autocomplete>
      <v-spacer></v-spacer>
      <v-autocomplete
        class="mt-6"
        :value.sync="modelo_id"
        :items="modelosFiltrados"
        item-text="nombre"
        item-value="id"
        label="Modelo"
        @input="UPDATE_MODELO_ID"
      ></v-autocomplete>
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
        max-width="30%"
      >
        <modal-version></modal-version>
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="versionesFiltradas"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editVersion(item.id)"
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
          <span>Esta seguro que desea eliminar esta Modelo?</span>
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
            @click="deleteModel"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../../../helpers";
import ModalVersion from "./ModalVersion";
export default {
  mixins: [helpers],
  components: { ModalVersion },
  data: () => ({
    search: "",
    modalDelete: false,
    headers: [
      { text: "Nombre", value: "nombre" },
      { text: "Acciones", value: "actions", sortable: false, align: "right" }
    ],
    idSelected: null
  }),
  computed: {
    ...mapState("modal", ["modal3"]),
    ...mapState("version", ["versiones", "modelo_id", "loading"]),
    ...mapState("marca", ["marcas"]),
    ...mapState("modelo", ["modelos", "marca_id"])
  },
  methods: {
    ...mapMutations("modal", ["SHOW_MODAL3", "HIDE_MODAL3"]),
    ...mapActions("modelo", ["updateMarcaId"]),
    ...mapMutations("version", ["UPDATE_MODELO_ID"]),
    ...mapActions("version", ["getVersion", "deleteVersion"]),
    editVersion(id) {
      this.getVersion(id);
      this.SHOW_MODAL3(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteModel() {
      this.deleteModelo(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    }
  }
};
</script>

<style>
</style>