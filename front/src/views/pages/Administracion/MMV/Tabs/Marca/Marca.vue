<template>
  <v-card
    class="mt-0 mx-4 pa-3"
    elevation=0
  >
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
        :retain-focus="false"
        @click:outside="HIDE_MODAL(false)"
        :value="modal"
        max-width="30%"
      >
        <modal-marca></modal-marca>
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="marcas"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editMarca(item.id)"
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
          <span>Esta seguro que desea eliminar esta Marca?</span>
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
            @click="deleteMarc"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
// import { helpers } from '../../../../helpers';
import ModalMarca from "./ModalMarca";
export default {
  components: { ModalMarca },
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
    ...mapState("modal", ["modal"]),
    ...mapState("marca", ["marcas", "loading"])
  },
  methods: {
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    ...mapActions("marca", ["getMarca", "deleteMarca"]),
    editMarca(id) {
      this.getMarca(id);
      this.SHOW_MODAL(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteMarc() {
      this.deleteMarca(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    }
  }
};
</script>

<style>
</style>