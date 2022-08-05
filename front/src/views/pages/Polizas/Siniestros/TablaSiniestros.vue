<template>
  <v-card class="mt-0 ml-2 mr-4 pa-3">
    <v-card-title>
      Siniestros
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        @click="SHOW_MODAL3(false)"
        dark
      >Crear</v-btn>
      <v-dialog
        @click:outside="HIDE_MODAL3(false)"
        @keydown.esc="HIDE_MODAL3(false)"
        :value="modal3"
        :max-width='edicion3 ?"75%" :"45%"'
        :retain-focus="false"
      >
        <modal-siniestros />
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="siniestros_poliza"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.fechaDenuncia`]="{ item }">
        {{
          dateToString(item.fecha_denuncia)
        }}
      </template>
      <template v-slot:[`item.fechaSiniestro`]="{ item }">
        {{
          dateToString(item.fecha_siniestro)
        }}
      </template>
      <template v-slot:[`item.fechaCompleto`]="{ item }">
        {{
          dateOrNo(item.fecha_completo)
        }}
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          @click="editSiniestro(item.id)"
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
          <span>Esta seguro que desea eliminar este Siniestro?</span>
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
            @click="deleteSin"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalSiniestros from "../../Pendientes/Siniestros/ModalSiniestros.vue";
import { helpers } from "../../../../helpers";

export default {
  components: {
    ModalSiniestros
  },
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: "",
    modalDelete: false
  }),
  computed: {
    ...mapState("modal", ["modal3", "edicion3"]),
    ...mapState("siniestro", ["siniestros_poliza", "loading"]),
    headers() {
      return [
        { text: "F. Denuncia", value: "fechaDenuncia" },
        { text: "F. Siniestro", value: "fechaSiniestro" },
        { text: "Numero", value: "numero_siniestro" },
        { text: "Completo", value: "fechaCompleto" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("siniestro", ["getSiniestro", "deleteSiniestro"]),
    ...mapMutations("modal", ["SHOW_MODAL3", "HIDE_MODAL3"]),
    editSiniestro(id) {
      this.getSiniestro(id);
      this.SHOW_MODAL3(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteSin() {
      this.deleteSiniestro(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    }
  }
};
</script>

<style>
</style>