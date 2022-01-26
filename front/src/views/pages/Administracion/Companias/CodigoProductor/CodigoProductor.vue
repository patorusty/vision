<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      Código Productor
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        @click="SHOW_MODAL2(false)"
        dark
      >Crear</v-btn>
      <v-dialog
        :retain-focus="false"
        @click:outside="HIDE_MODAL2(false)"
        :value="modal2"
        max-width="40%"
      >
        <modal-codigo-productor />
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="codigo_productores"
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
          @click.stop="editCodigoProductor(item.id)"
          class="mr-2"
          color="success"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          class="ml-2"
          small
          @click.stop="openDeleteModal(item.id)"
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
          <span>Esta seguro que desea eliminar este Código Productor?</span>
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
            @click="deleteCodProd"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalCodigoProductor from "./ModalCodigoProductor";
import { helpers } from "../../../../../helpers";

export default {
  components: {
    ModalCodigoProductor
  },
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: "",
    modalDelete: false
  }),
  computed: {
    ...mapState("compania", ["loading"]),
    ...mapState("codigo_productor", ["codigo_productores"]),
    ...mapState("modal", ["modal2", "edicion2"]),
    headers() {
      return [
        { text: "Apellido", value: "productores.apellido" },
        { text: "Nombre", value: "productores.nombre" },
        { text: "Matricula", value: "productores.matricula" },
        {
          text: "Codigo Productor",
          value: "codigo_productor"
        },
        {
          text: "Codigo Organizador",
          value: "codigo_organizador.codigo_organizador"
        },
        { text: "Activo", value: "activo" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("productor", ["getProductoresActivos"]),
    ...mapActions("codigo_productor", [
      "getCodigoProductor",
      "deleteCodigoProductor"
    ]),
    ...mapMutations("modal", ["SHOW_MODAL2", "HIDE_MODAL2"]),
    editCodigoProductor(id) {
      this.getCodigoProductor(id);
      this.SHOW_MODAL2(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteCodProd() {
      this.deleteCodigoProductor(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    }
  },
  created() {
    this.getProductoresActivos();
  }
};
</script>

<style>
</style>