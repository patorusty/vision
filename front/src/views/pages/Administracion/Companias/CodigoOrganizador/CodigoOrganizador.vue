<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      Código Organizador
      <v-spacer></v-spacer>

      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        @click="SHOW_MODAL(false)"
        dark
      >Crear</v-btn>
      <v-dialog
        @click:outside="HIDE_MODAL(false)"
        :value="modal"
        max-width="40%"
      >
        <modal-codigo-organizador />
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="codigo_organizadores"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template
        slot="item.activo"
        slot-scope="props"
      >{{
        textoActivo(props.item.activo)
      }}</template>
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
          <span>Esta seguro que desea eliminar este Código Organizador?</span>
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
            @click="deleteCodOrg"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalCodigoOrganizador from "./ModalCodigoOrganizador";
import { helpers } from "../../../../../helpers";

export default {
  components: {
    ModalCodigoOrganizador
  },
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: "",
    modalDelete: false
  }),
  computed: {
    ...mapState("compania", ["loading"]),
    ...mapState("codigo_organizador", ["codigo_organizadores"]),
    ...mapState("modal", ["modal", "edicion"]),
    headers() {
      return [
        { text: "Apellido", value: "organizadores.apellido" },
        { text: "Nombre", value: "organizadores.nombre" },
        { text: "Matricula", value: "organizadores.matricula" },
        {
          text: "Codigo Organizador",
          value: "codigo_organizador"
        },
        { text: "Activo", value: "activo" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("organizador", ["getOrganizadores"]),
    ...mapActions("codigo_organizador", [
      "getCodigoOrganizador",
      "deleteCodigoOrganizador"
    ]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    editCodigoOrganizador(id) {
      this.getCodigoOrganizador(id);
      this.SHOW_MODAL(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteCodOrg() {
      this.deleteCodigoOrganizador(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    }
  },
  created() {
    this.getOrganizadores();
  }
};
</script>

<style>
</style>