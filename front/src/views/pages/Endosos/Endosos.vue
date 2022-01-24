<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      <v-row>
        <v-col
          cols="4"
          class="pb-0"
        >
          <v-select
            v-model="tipo_endoso_id"
            :items="tipoEndosos"
            item-value="id"
            item-text="nombre"
            label="Tipo Endosos"
            class="mr-5"
          ></v-select>
        </v-col>
      </v-row>
      <v-spacer></v-spacer>
      <v-dialog
        @click:outside="HIDE_MODAL2(false)"
        :value="modal2"
        max-width="40%"
      >
        <modal-endosos />
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="tableData"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.tipo`]="{ item }">
        {{
          item.tipo_endoso.nombre
        }}
      </template>
      <template v-slot:[`item.asegurado`]="{ item }">
        {{
          nombreCompleto(item.poliza.cliente)
        }}
      </template>
      <template v-slot:[`item.compania`]="{ item }">
        {{
          item.poliza.compania.nombre
        }}
      </template>
      <template v-slot:[`item.fecha_solicitud`]="{ item }">
        {{
          formatDate(item.fecha_solicitud)
        }}
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          @click="editEndoso(item.id)"
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
          <span>Esta seguro que desea eliminar este Endoso?</span>
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
            @click="deleteEnd"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalEndosos from "./ModalEndosos";
import { helpers } from "../../../helpers";

export default {
  components: {
    ModalEndosos
  },
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: "",
    modalDelete: false,
    tipo_endoso_id: 0
  }),
  computed: {
    ...mapState("modal", ["modal2", "edicion2"]),
    ...mapState("endoso", ["endosos", "tipo_endosos", "loading"]),
    headers() {
      return [
        { text: "Póliza Nro.", value: "poliza.numero" },
        { text: "Tipo", value: "tipo" },
        { text: "Asegurado", value: "asegurado" },
        { text: "Compañia", value: "compania" },
        { text: "F. Solicitud", value: "fecha_solicitud" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    },
    tableData() {
      return this.endosos.filter(item =>
        this.tipo_endoso_id != 0
          ? item.tipo_endoso_id === this.tipo_endoso_id
          : item.tipo_endoso_id != 0
      );
    },
    tipoEndosos() {
      var r = [...this.tipo_endosos, { id: 0, nombre: "TODOS" }];
      return r;
    }
  },
  methods: {
    ...mapActions("endoso", [
      "getEndosos",
      "getEndoso",
      "deleteEndoso",
      "getTipoEndosos",
      "getDetalleEndosos"
    ]),
    ...mapMutations("modal", ["SHOW_MODAL2", "HIDE_MODAL2"]),
    editEndoso(id) {
      this.getEndoso(id);
      this.SHOW_MODAL2(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteEnd() {
      this.deleteEndoso(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    }
  },
  created() {
    this.getEndosos();
    this.getTipoEndosos();
    this.getDetalleEndosos();
  }
};
</script>

<style>
</style>