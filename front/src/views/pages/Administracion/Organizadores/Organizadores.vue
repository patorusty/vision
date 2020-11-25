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
      ></v-text-field>
      <v-spacer></v-spacer>
      <v-btn color="primary" @click="SHOW_MODAL(false)" dark>Crear</v-btn>
      <v-dialog @click:outside="HIDE_MODAL(false)" :value="modal" max-width="70%">
        <modal-organizadores></modal-organizadores>
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="organizadores"
      :search="search"
      multi-sort
    >
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editOrganizador(item.id)"
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
    <v-dialog :retain-focus="false" max-width="30%" v-model="modalDelete">
      <v-card class="pa-4">
        <v-card-text>
          <span>Esta seguro que desea eliminar este organizador?</span>
        </v-card-text>
        <v-card-actions class="py-0 pt-3 pr-6 d-flex justify-end">
          <v-btn dark color="red" @click="modalDelete = false">Cancelar</v-btn>
          <v-btn class="ml-4" dark color="success" @click="deleteOrg"
            >Confirmar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalOrganizadores from "./ModalOrganizadores";

export default {
  components: {
    ModalOrganizadores,
  },
  data() {
    return {
      search: "",
      idSelected: null,
      modalDelete: false,
      headers: [
        { text: "Apellido", value: "apellido" },
        { text: "Nombre", value: "nombre" },
        { text: "cuit", value: "cuit" },
        { text: "Mail", value: "email" },
        { text: "celular", value: "telefono_2" },
        { text: "Activo", value: "activo" },
        { text: "Acciones", value: "actions", sortable: false, align: "right" },
      ],
    };
  },
  computed: {
    ...mapState("organizadores", ["organizadores"]),
    ...mapState("modal", ["modal"]),
  },
  methods: {
    ...mapActions("organizadores", [
      "getOrganizadores",
      "getOrganizador",
      "deleteOrganizador",
    ]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    editOrganizador(nombre) {
      this.getOrganizador(nombre);
      this.SHOW_MODAL(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteOrg() {
      this.deleteOrganizador(this.idSelected);
      this.modalDelete = false;
    },
    textoActivo(nro) {
      return nro === 1 ? "Activo" : "Inactivo";
    },
  },
  created() {
    this.getOrganizadores();
  },
};
</script>