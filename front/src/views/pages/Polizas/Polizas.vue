<template>
  <v-card
    style="max-width: 1840px;"
    class="mt-0 mx-4 pa-3"
  >
    <v-card-title>
      <v-row>
        <v-col
          cols="3"
          class="pb-0"
        >
          <v-select
            v-model="search.tipo_riesgo_id"
            :items="riesgos"
            item-value="id"
            item-text="nombre"
            label="Tipo Riesgo"
            class="mr-5"
          ></v-select>
        </v-col>
        <v-col
          cols="7"
          class="pb-0"
        >
          <v-spacer></v-spacer>
        </v-col>
        <v-col
          cols="2"
          class="pb-0"
        >
          <v-btn
            color="primary"
            to="/polizas/automotor/create"
            dark
            class="ml-5"
          >Crear</v-btn>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="4">
          <!-- <v-text-field
            v-model="cliente"
            label="Cliente"
            single-line
            hide-details
            class="mr-3 ml-3"
            v-uppercase
          ></v-text-field> -->
          <v-autocomplete
            v-model="search.cliente_id"
            :items="clientes"
            item-value="id"
            :item-text="nombreCompleto"
            label="Cliente"
            clearable
            @click:clear="$nextTick(() => (search.cliente_id = 0))"
          ></v-autocomplete>
        </v-col>
        <v-col>
          <v-text-field
            v-model="search.poliza"
            label="Nro de Poliza"
            single-line
            hide-details
            class="mr-3 ml-3"
          ></v-text-field>
        </v-col>
        <v-col>
          <v-text-field
            v-model="search.patente"
            label="Patente"
            single-line
            hide-details
            class="mr-3 ml-3"
            v-uppercase
          ></v-text-field>
        </v-col>
        <v-col>
          <v-select
            v-model="search.compania_id"
            :items="companias"
            item-value="id"
            item-text="nombre"
            label="Compania"
            class="mr-5"
            :clearable="search.compania_id != 0"
            @click:clear="$nextTick(() => (search.compania_id = 0))"
          ></v-select>
        </v-col>
        <v-col>
          <v-autocomplete
            no-data-text="Sin Datos"
            v-model="search.filtroEstado"
            :items="estados"
            multiple
            item-text="nombre"
            item-value="id"
            label="Estado"
            :clearable="search.filtroEstado != 0"
            @click:clear="$nextTick(() => (search.filtroEstado = []))"
          >
            <template v-slot:selection="{ item, index }">
              <span v-if="index === 0">{{ item.nombre }}</span>
            </template>
          </v-autocomplete>
        </v-col>
      </v-row>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="10"
      :items="tableData"
      multi-sort
      :loading="loading"
      :no-data-text='polizas.length === 0 ? "Polizas no cargadas" : "Aplique un filtro de busqueda"'
      loading-text='Cargando...'
      :item-class="itemRowColor"
    >
      <template v-slot:[`item.poliza`]="{ item }">
        <div v-if="item.numero">{{ item.numero }}</div>
      </template>
      <template v-slot:[`item.compania`]="{ item }">{{ item.compania.nombre }} <br />
        Cod.({{ item.codigo_productor.codigo_productor }})</template>
      <template v-slot:[`item.asegurado`]="{ item }">
        <router-link
          v-if="item.cliente"
          class="links"
          :to=" {name: 'Editar Cliente', params: {id: item.cliente.id}}"
          target="_blank"
        >{{
          nombreCompleto(item.cliente)
        }}
        </router-link>
      </template>
      <template v-slot:[`item.dominio`]="{ item }">
        <span v-if="item.tipo_riesgo_id == 1">{{dominio(item)}}</span>
        <v-icon v-else-if="item.tipo_riesgo_id == 2">mdi-account-group</v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 3"> mdi-fire </v-icon>
        <span v-else-if="item.tipo_riesgo_id == 4">
          <v-icon>mdi-alpha-r </v-icon>
          <v-icon> mdi-alpha-c </v-icon>
        </span>
        <v-icon v-else-if="item.tipo_riesgo_id == 5"> mdi-storefront </v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 6"> mdi-medical-bag </v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 7"> mdi-bicycle </v-icon>
        <v-icon v-else-if="item.tipo_riesgo_id == 8"> mdi-sail-boat </v-icon>
      </template>
      <template v-slot:[`item.desde`]="{ item }">{{ dateToString(item.vigencia_desde) }} <br />
        {{ dateToString(item.vigencia_hasta) }}</template>
      <template v-slot:[`item.estado`]="{ item }">
        <v-chip
          dark
          color="deep-orange lighten-1"
          v-if="item.estado_poliza_id == 4"
        >{{item.estado.nombre}}</v-chip>
        <span v-else>{{item.estado.nombre}}</span>
      </template>
      <template v-slot:[`item.envio`]="{ item }">
        <div v-if="
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_email === null
        ">
          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                color="blue"
                v-bind="attrs"
                v-on="on"
              >mdi-hand-extended-outline</v-icon>
            </template>
            <span>Recibida</span>
          </v-tooltip>
        </div>
        <div v-else-if="
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_email !== null
      ">
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-icon
                color="success"
                v-on="on"
              >mdi-email-fast-outline</v-icon>
            </template>
            <span>Email</span>
          </v-tooltip>
        </div>
        <div v-else-if="
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_email === null
      ">
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-icon
                color="success"
                v-on="on"
              >mdi-hand-extended-outline</v-icon>
            </template>
            <span>Entregada</span>
          </v-tooltip>
        </div>
        <div v-else-if="
          item.fecha_recepcion !== null &&
          item.fecha_entrega_original !== null &&
          item.fecha_entrega_email !== null
          ">
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-icon
                color="success"
                v-on="on"
              >mdi-hand-extended-outline</v-icon>
            </template>
            <span>Entregada</span>
          </v-tooltip><span> / </span>
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-icon
                color="success"
                v-on="on"
              >mdi-email-fast-outline</v-icon>
            </template>
            <span>Email</span>
          </v-tooltip>
        </div>
        <div v-else>
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-icon
                color="red"
                v-on="on"
              >mdi-file-cancel-outline</v-icon>
            </template>
            <span>No Recibida</span>
          </v-tooltip>
        </div>
      </template>
      <template v-slot:[`item.pago`]="{ item }">{{ formaDePago(item) }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <router-link
          class="links"
          :to="{ name: 'Editar Poliza', params: { id: item.id } }"
        >
          <v-icon
            small
            class="mr-2"
            color="success"
          > mdi-pencil </v-icon>
        </router-link>
        <v-icon
          small
          v-on:click.stop="openRenewModal(item.id)"
          color="success"
        >
          mdi-refresh
        </v-icon>
        <v-icon
          class="ml-2"
          small
          v-on:click.stop="openDeleteModal(item.id)"
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
          <span>Esta seguro que desea eliminar esta Póliza?</span>
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
            @click="deletePolicy"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../helpers";
import { bus } from "../.././../main";

export default {
  components: {},
  mixins: [helpers],
  data: () => ({
    idSelected: null,
    modalDelete: false
  }),
  computed: {
    ...mapState("poliza", [
      "polizas",
      "loading",
      "tipo_riesgos",
      "estados",
      "search"
    ]),
    ...mapState("compania", ["companias"]),
    ...mapState("cliente", ["clientes"]),
    tableData() {
      let tempPolizas = this.polizas.filter(
        item =>
          (this.search.compania_id != 0
            ? item.compania_id == this.search.compania_id
            : item.compania_id != 0) &&
          (this.search.cliente_id != 0
            ? item.cliente_id == this.search.cliente_id
            : item.cliente_id != 0) &&
          (this.search.patente == "" && item.tipo_riesgo_id == 1
            ? item.riesgo_automotor
            : item.riesgo_automotor.find(riesgo =>
                riesgo.patente.includes(this.search.patente)
              )) &&
          (this.search.filtroEstado.length > 0
            ? this.search.filtroEstado.includes(item.estado_poliza_id)
            : item.estado_poliza_id != 0) &&
          (this.search.poliza != ""
            ? item.numero && item.numero.includes(this.search.poliza)
            : item.numero != "")
      );

      return this.search.cliente_id == 0 &&
        this.search.compania_id == 0 &&
        this.search.patente == "" &&
        this.search.poliza == "" &&
        this.search.filtroEstado.length == 0
        ? []
        : tempPolizas.filter(
            item => item.tipo_riesgo_id === this.search.tipo_riesgo_id
          );
    },
    riesgos() {
      var r = [...this.tipo_riesgos, { id: 0, nombre: "TODOS" }];
      return r;
    },
    headers() {
      return [
        {
          text: "Poliza N°",
          value: "numero",
          filterable: true,
          filter: value => {
            if (!this.poliza) return true;
            return (value + "")
              .toLowerCase()
              .includes(this.poliza.toLowerCase());
          },
          align: "start"
        },
        {
          text: "Compania",
          value: "compania",
          filterable: true
        },
        { text: "Asegurado", value: "asegurado" },
        { text: "Dominio", value: "dominio" },
        { text: "Vigencia", value: "tipo_vigencias.vigencia" },
        { text: "Desde / Hasta", value: "desde" },
        { text: "Estado", value: "estado" },
        { text: "Envío", value: "envio" },
        { text: "Pago", value: "pago" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    }
  },
  methods: {
    ...mapActions("poliza", [
      "getPolizas",
      "renewPoliza",
      "deletePoliza",
      "getTipoRiesgos",
      "getEstados"
    ]),
    ...mapMutations("poliza", ["CLEAN_SEARCH"]),
    ...mapActions("compania", ["getCompanias"]),
    ...mapActions("cliente", ["getClientes"]),
    filterRazon(item) {
      if (this.cliente != "" && item.cliente.razon_social != null) {
        return item.cliente.razon_social.includes(this.cliente);
      }
    },
    filtroTipoRiesgo(item) {
      if (this.tipo_riesgo_id != 0)
        return (item.tipo_riesgo_id = this.tipo_riesgo_id);
    },
    filtroClientes(item) {
      if (this.cliente_id != 0) return (item.cliente_id = this.cliente_id);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deletePolicy() {
      this.deletePoliza(this.idSelected);
      this.modalDelete = false;
    },
    dominio(item) {
      if (item.tipo_riesgo_id == 1 && item.riesgo_automotor.length > 0) {
        return item.riesgo_automotor.length > 1
          ? "Flota"
          : item.riesgo_automotor[0].patente;
      }
      // else if (item.tipo_riesgo_id == 2 && item.otro_riesgo.length > 0) {
      //   return `<v-icon small class="mr-2" color="success"> mdi-pencil </v-icon>`;
      // }
    },
    openRenewModal(id) {
      this.renewPoliza(id);
    },
    itemRowColor(item) {
      switch (item.estado_poliza_id) {
        case 1:
          return "red lighten-4";
        case 4:
          return "white";
        case 3:
        case 7:
          return "white";
        case 5:
        case 6:
          return "light-green lighten-4";
        case 2:
          return "blue-grey lighten-4";
      }
    },
    formaDePago(item) {
      if (item.forma_pago_id == 1) {
        return "TC";
      } else if (item.forma_pago_id == 2) {
        return "DC";
      } else {
        return "PF / RP";
      }
    }
  },
  mounted() {
    this.getPolizas();
    this.getClientes();
    this.getCompanias();
    this.getTipoRiesgos();
    this.getEstados();
  },
  created() {
    bus.$on("cleanSearch", () => this.CLEAN_SEARCH());
  }
};
</script>

<style lang="scss" scoped>
.links {
  text-decoration: none;
}
.titulo-polizas {
  display: flex;
  justify-content: space-evenly;
}
</style>
