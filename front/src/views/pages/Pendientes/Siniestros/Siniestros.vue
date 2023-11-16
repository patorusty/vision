<template>
  <v-card class="mt-0 mx-4 pa-3" elevation=0>
    <v-card-title>
      <v-dialog :retain-focus="false" @click:outside="HIDE_MODAL3(false)" @keydown.esc="HIDE_MODAL3(false)"
        :value="modal3" :max-width='edicion3 ? "75%" : "45%"'>
        <modal-siniestros />
      </v-dialog>
    </v-card-title>
    <v-data-table class="pa-2" :headers="headers" :items-per-page="5" :items="siniestros_activos" :search="search"
      multi-sort :loading="loading" :item-class="itemRowBackground">
      <template v-slot:[`item.tipo_reclamo`]="{ item }">
        {{ item.tipo_reclamo }}
      </template>
      <template v-slot:[`item.asegurado`]="{ item }">
        {{
          nombreCompleto(item.poliza.cliente)
        }}
      </template>
      <template v-slot:[`item.fechaSiniestro`]="{ item }">
        {{
          dateToString(item.fecha_siniestro)
        }}
      </template>
      <template v-slot:[`item.inspeccion`]="{ item }">
        <v-icon v-if="item.inspeccion == 1">mdi-file-check-outline</v-icon>
      </template>
      <template v-slot:[`item.en_taller`]="{ item }">
        <v-icon v-if="item.en_taller == 1">mdi-wrench</v-icon>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small @click="editSiniestro(item.id)" class="mr-2" color="success">
          mdi-pencil
        </v-icon>
        <v-icon class="ml-2" small @click="openDeleteModal(item.id)" color="error">
          mdi-close
        </v-icon>
      </template>
    </v-data-table>
    <v-dialog :retain-focus="false" max-width="30%" v-model="modalDelete">
      <v-card class="pa-4">
        <v-card-text>
          <span>Esta seguro que desea eliminar este Siniestro?</span>
        </v-card-text>
        <v-card-actions class="py-0 pt-3 pr-6 d-flex justify-end">
          <v-btn dark color="red" @click="modalDelete = false">Cancelar</v-btn>
          <v-btn class="ml-4" dark color="success" @click="deleteSin">Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalSiniestros from "./ModalSiniestros.vue";
import { helpers } from "../../../../helpers";

export default {
  components: {
    ModalSiniestros
  },
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: "",
    modalDelete: false,
    headers: [
      { text: "F. Siniestro", value: "fechaSiniestro" },
      { text: "Compañia", value: "poliza.compania.nombre" },
      { text: "Póliza Nro.", value: "poliza.numero" },
      { text: "Numero", value: "numero_siniestro" },
      { text: "Asegurado", value: "asegurado" },
      { text: "Tipo de Reclamo", value: "tipo_reclamo" },
      { text: "Inspección", value: "inspeccion", align: 'center' },
      { text: "En Taller", value: "en_taller", align: 'center' },
      { text: "Actions", value: "actions", sortable: false, align: "right" }
    ]
  }),
  computed: {
    ...mapState("modal", ["modal3", "edicion3"]),
    ...mapState("siniestro", ["siniestros_activos", "loading"])
  },
  methods: {
    ...mapActions("siniestro", [
      "getSiniestrosActivos",
      "getSiniestro",
      "deleteSiniestro"
    ]),
    ...mapMutations("modal", ["SHOW_MODAL3", "HIDE_MODAL3"]),
    ...mapMutations("siniestro", ["RESET_SINIESTROS"]),
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
  },
  created() {
    this.getSiniestrosActivos();
  }
};
</script>
<style>
.style-1 {
  background-color: rgb(253, 161, 40);
}

.style-2 {
  background-color: rgb(149, 57, 253);
}

.style-3 {
  background-color: rgb(134, 223, 107);
}

.style-4 {
  background-color: rgb(223, 215, 107);
}

.style-5 {
  background-color: rgb(255, 79, 79);
}
</style>