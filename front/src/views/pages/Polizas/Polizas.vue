<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-card-title>
      <v-row>
        <v-col
          cols="3"
          class="pb-0"
        >
          <v-select
            v-model="tipo_riesgo_id"
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
          <v-text-field
            v-model="cliente"
            label="Cliente"
            single-line
            hide-details
            class="mr-3 ml-3"
            v-uppercase
          ></v-text-field>
        </v-col>
        <v-col>
          <v-text-field
            v-model="poliza"
            label="Nro de Poliza"
            single-line
            hide-details
            class="mr-3 ml-3"
          ></v-text-field>
        </v-col>
        <v-col>
          <v-text-field
            v-model="patente"
            label="Patente"
            single-line
            hide-details
            class="mr-3 ml-3"
            v-uppercase
          ></v-text-field>
        </v-col>
        <v-col>
          <v-select
            v-model="compania_id"
            :items="companias"
            item-value="id"
            item-text="nombre"
            label="Compania"
            class="mr-5"
            :clearable="compania_id != 0"
            @click:clear="$nextTick(() => (compania_id = 0))"
          ></v-select>
        </v-col>
        <v-col>
          <v-autocomplete
            no-data-text="Sin Datos"
            v-model="filtroEstado"
            :items="estados"
            multiple
            item-text="nombre"
            item-value="id"
            label="Estado"
            :clearable="filtroEstado != 0"
            @click:clear="$nextTick(() => (filtroEstado = []))"
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
    >
      <template v-slot:[`item.compania`]="{ item }">{{ item.compania.nombre }} <br />
        Cod.({{ item.codigo_productor.codigo_productor }})</template>
      <template v-slot:[`item.asegurado`]="{ item }">
        <router-link
          class="links"
          :to=" {name: 'Editar Cliente', params: {id: item.cliente.id}}"
          target="_blank"
        >{{ item.cliente.nombre }} {{ item.cliente.apellido }}
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
      <template v-slot:[`item.desde`]="{ item }">{{ formatDate(item.vigencia_desde) }} <br />
        {{ formatDate(item.vigencia_hasta) }}</template>
      <template v-slot:[`item.estado`]="{ item }">
        <v-chip
          :color="itemRowColor(item)"
          dark
        >{{item.estado.nombre}}</v-chip>
      </template>
      <template v-slot:[`item.envio`]="{ item }">{{ envio(item) }}</template>
      <template v-slot:[`item.pago`]="{ item }">{{ formaDePago(item) }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <router-link
          class="links"
          :to="{ name: 'Editar Poliza', params: { numero_solicitud: item.numero_solicitud } }"
        >
          <v-icon
            small
            class="mr-2"
            color="success"
          > mdi-pencil </v-icon>
        </router-link>
        <v-icon
          small
          v-on:click.stop="openRenewModal(item.numero_solicitud)"
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
import { mapState, mapActions } from "vuex";
import { helpers } from "../../../helpers";
export default {
  components: {},
  mixins: [helpers],
  data: () => ({
    tipo_riesgo_id: 1,
    poliza: "",
    patente: "",
    compania_id: 0,
    cliente: "",
    idSelected: null,
    modalDelete: false,
    filtroEstado: []
  }),
  computed: {
    ...mapState("poliza", ["polizas", "loading", "tipo_riesgos", "estados"]),
    ...mapState("compania", ["companias"]),
    tableData() {
      return this.polizas.filter(
        item =>
          (this.tipo_riesgo_id != 0
            ? item.tipo_riesgo_id === this.tipo_riesgo_id
            : item.tipo_riesgo_id != 0) &&
          (this.compania_id != 0
            ? item.compania_id === this.compania_id
            : item.compania_id != 0) &&
          (this.filtroEstado.length > 0
            ? this.filtroEstado.includes(item.estado_poliza_id)
            : item.estado_poliza_id != 0) &&
          (this.cliente != ""
            ? item.cliente.apellido.includes(this.cliente) ||
              item.cliente.nombre.includes(this.cliente)
            : item.cliente.apellido) &&
          (this.patente != "" && item.tipo_riesgo_id == 1
            ? item.riesgo_automotor.find(riesgo =>
                riesgo.patente.includes(this.patente)
              )
            : item.riesgo_automotor) &&
          (this.compania_id != 0 && item.compania_id != null
            ? item.compania_id === this.compania_id
            : item.tipo_riesgo_id != 0)
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
          }
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
    ...mapActions("compania", ["getCompanias"]),
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
          ? "Autos"
          : item.riesgo_automotor[0].patente;
      }
      // else if (item.tipo_riesgo_id == 2 && item.otro_riesgo.length > 0) {
      //   return `<v-icon small class="mr-2" color="success"> mdi-pencil </v-icon>`;
      // }
    },
    openRenewModal(numero_solicitud) {
      this.renewPoliza(numero_solicitud);
    },
    itemRowColor(item) {
      switch (item.estado_poliza_id) {
        case 1:
          return "red lighten-1";
        case 4:
        case 5:
          return "light-green lighten-2";
        case "SIN RECLAMO (Daño a Tercero)":
          return "light-green lighten-2";
        case 7:
          return "grey lighten-2";
      }
    },
    envio(item) {
      if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo === null &&
        item.fecha_entrega_email === null
      ) {
        return "Recibida";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email === null
      ) {
        return "Enviada por correo";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo === null &&
        item.fecha_entrega_email !== null
      ) {
        return "Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo === null &&
        item.fecha_entrega_email === null
      ) {
        return "Entregada";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo === null &&
        item.fecha_entrega_email !== null
      ) {
        return "Entregada / Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email !== null
      ) {
        return "Entregada / Enviada por Correo / Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email === null
      ) {
        return "Entregada / Enviada por Correo";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email !== null
      ) {
        return "Enviada por Correo / Email";
      } else {
        return "No Recibida";
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
  created() {
    this.getPolizas();
    this.getCompanias();
    this.getTipoRiesgos();
    this.getEstados();
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
