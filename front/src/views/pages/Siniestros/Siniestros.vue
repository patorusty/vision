<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      <v-dialog :retain-focus="false" @click:outside="HIDE_MODAL3(false)" @keydown.esc="HIDE_MODAL3(false)"
        :value="modal3" :max-width='edicion3 ? "75%" : "45%"'>
        <modal-siniestros />
      </v-dialog>
      <v-row>
        <v-col>
          <v-autocomplete v-model="search.cliente_id" :items="clientes" item-value="id" :item-text="nombreCompleto"
            label="Cliente" clearable @click:clear="$nextTick(() => (search.cliente_id = 0))"></v-autocomplete>
        </v-col>
        <v-col>
          <v-select v-model="search.compania_id" :items="companias" item-value="id" item-text="nombre" label="Compania"
            class="mr-5" :clearable="search.compania_id != 0"
            @click:clear="$nextTick(() => (search.compania_id = 0))"></v-select>
        </v-col>
        <v-col cols="2">
          <v-select label="Año" :items=activeYears v-model="search.anio" clearable
            @click:clear="$nextTick(() => (search.anio = null))">
          </v-select>
        </v-col>
        <v-col>
          <v-text-field v-model="search.siniestro" label="Nro de Siniestro" single-line hide-details
            class="mr-3 ml-3"></v-text-field>
        </v-col>
        <v-col>
          <v-text-field v-model="search.poliza" label="Nro de Poliza" single-line hide-details
            class="mr-3 ml-3"></v-text-field>
        </v-col>
        <v-col>
          <v-autocomplete no-data-text="Sin Datos" v-model="search.filtroEstado" :items="tipo_reclamos" multiple
            item-text="value" item-value="value" label="Tipo de Reclamo" :clearable="search.filtroEstado != 0"
            @click:clear="$nextTick(() => (search.filtroEstado = []))">
            <template v-slot:selection="{ item, index }">
              <span v-if="index === 0">{{ item.value }}</span>
            </template>
          </v-autocomplete>
        </v-col>
        <v-col>
          <v-select v-model="search.estado" item-text="value" item-value="value" :items="estados" label="Estado"
            :clearable="search.estado != 'Todos'" @click:clear="$nextTick(() => (search.estado = 'Todos'))">
          </v-select>
        </v-col>
      </v-row>
    </v-card-title>
    <v-data-table class="pa-2" :headers="headers" :items-per-page="10" :items="tableData" :loading="loading"
      :item-class="itemRowBackground"
      :no-data-text='siniestros.length === 0 ? "Siniestros no cargados" : "Aplique un filtro de busqueda"'
      loading-text='Cargando...'>
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
        <v-icon v-if="item.en_taller == 1">mdi-cog</v-icon>
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
import ModalSiniestros from "../Pendientes/Siniestros/ModalSiniestros.vue";
import { helpers } from "../../../helpers";
import { bus } from "../../../main";
import moment from "moment";

export default {
  components: {
    ModalSiniestros
  },
  mixins: [helpers],
  data: () => ({
    idSelected: "",
    modalDelete: false,
    headers: [
      { text: "F. Siniestro", value: "fechaSiniestro" },
      { text: "Compañia", value: "poliza.compania.nombre" },
      { text: "Póliza Nro.", value: "poliza.numero" },
      { text: "Numero", value: "numero_siniestro" },
      { text: "Asegurado", value: "asegurado" },
      { text: "Tipo de Reclamo", value: "tipo_reclamo" },
      { text: "Estado", value: "estado_siniestro" },
      { text: "Inspección", value: "inspeccion", align: 'center' },
      { text: "En Taller", value: "en_taller", align: 'center' },
      { text: "Actions", value: "actions", sortable: false, align: "right" }
    ],
    estados: [{ value: "Abierto" }, { value: "Cerrado" }, { value: "Todos" }]
  }),
  computed: {
    ...mapState("modal", ["modal3", "edicion3"]),
    ...mapState("poliza", ["search"]),
    ...mapState("cliente", ["clientes"]),
    ...mapState("compania", ["companias"]),
    ...mapState("siniestro", ["siniestros", "loading", "tipo_reclamos"]),
    tableData() {
      var filtroEstados = item => {
        if (this.search.estado != "Todos") {
          return item.estado_siniestro.includes(this.search.estado);
        } else if (this.search.estado == "Todos") {
          return true;
        }
      };
      let tempSiniestros = this.siniestros.filter(
        item =>
          (this.search.cliente_id != 0
            ? item.poliza.cliente_id == this.search.cliente_id
            : item.poliza.cliente_id != 0) &&
          (this.search.compania_id != 0
            ? item.poliza.compania_id == this.search.compania_id
            : item.poliza.compania_id != 0) &&
          (this.search.anio != null
            ? moment(item.fecha_siniestro).year() == this.search.anio
            : item.fecha_siniestro != null) &&
          (this.search.siniestro != "" && item.numero_siniestro != null
            ? item.numero_siniestro.includes(this.search.siniestro)
            : item.numero_siniestro != "") &&
          (this.search.filtroEstado.length > 0
            ? this.search.filtroEstado.find(e => e.includes(item.tipo_reclamo))
            : item.tipo_reclamo != null) &&
          (this.search.poliza != "" && item.poliza.numero != null
            ? item.poliza.numero.includes(this.search.poliza)
            : item.poliza.numero != null) &&
          (this.search.estado != ""
            ? filtroEstados(item)
            : item.estado_siniestro != 0)
      );
      return this.search.cliente_id == 0 &&
        this.search.siniestro == "" &&
        this.search.estado == "" &&
        this.search.poliza == "" &&
        this.search.anio == null &&
        this.search.filtroEstado.length == 0
        ? []
        : tempSiniestros;
    },
    activeYears() {
      var a = this.siniestros.map(s => {
        if (s.fecha_siniestro != null) return moment(s.fecha_siniestro).year();
      });
      return a.sort(function (a, b) {
        return b - a;
      });
    }
  },
  methods: {
    ...mapActions("siniestro", [
      "getSiniestros",
      "getSiniestro",
      "deleteSiniestro"
    ]),
    ...mapActions("cliente", ["getClientes"]),
    ...mapActions("compania", ["getCompanias"]),
    ...mapMutations("modal", ["SHOW_MODAL3", "HIDE_MODAL3"]),
    ...mapMutations("poliza", ["CLEAN_SEARCH"]),
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
    this.getClientes();
    this.getSiniestros();
    this.getCompanias();
    bus.$on("cleanSearch", () => this.CLEAN_SEARCH());
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