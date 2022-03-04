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
        v-uppercase
      ></v-text-field>
      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        @click="createCliente"
        dark
      >Crear</v-btn>
      <v-dialog
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        @click:outside="HIDE_MODAL(false)"
        @keydown.esc="HIDE_MODAL(false)"
        :value="modal"
        max-width="80%"
      >
        <modal-cliente />
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="10"
      :items="clientesFiltrados"
      :loading="loading"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
    >
      <template v-slot:[`item.nombre`]="{ item }">
        {{
          nombreCompleto(item)
        }}
      </template>
      <template v-slot:[`item.documento`]="{ item }">
        {{ item.cuit ? item.cuit : item.nro_dni }}
      </template>
      <template v-slot:[`item.productor`]="{ item }">{{ nombreCompleto(item.productores) }}</template>

      <template
        slot="item.activo"
        slot-scope="props"
      >{{
        textoActivo(props.item.activo)
      }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          small
          class="mr-2"
          color="success"
          v-on:click.stop="editCliente(item)"
        > mdi-pencil </v-icon>
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
          <span>Esta seguro que desea eliminar esta Cliente?</span>
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
            @click="deleteClient"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import ModalCliente from "./ModalCliente.vue";
import { helpers } from "../../../helpers";
export default {
  mixins: [helpers],
  components: { ModalCliente },
  data: () => ({
    search: "",
    idSelected: null,
    modalDelete: false,
    sortBy: "apellido",
    sortDesc: false
  }),
  computed: {
    ...mapState("cliente", ["clientes", "loading"]),
    ...mapState("modal", ["modal"]),
    headers() {
      return [
        { text: "Nombre", value: "nombre" },
        { text: "DNI / CUIT", value: "documento" },
        { text: "Celular", value: "celular" },
        { text: "E-mail", value: "email" },
        { text: "Productor", value: "productor" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ];
    },
    clientesFiltrados() {
      return this.clientes.filter(
        c =>
          // c.email.toUpperCase().includes(this.search.toUpperCase()) ||
          (c.nombre
            ? c.nombre.toUpperCase().includes(this.search.toUpperCase())
            : false) ||
          (c.apellido
            ? c.apellido.toUpperCase().includes(this.search.toUpperCase())
            : false) ||
          (c.nro_dni ? c.nro_dni.toString().includes(this.search) : false) ||
          (c.razon_social
            ? c.razon_social.toString().includes(this.search)
            : false) ||
          (c.cuit ? c.cuit.toString().includes(this.search) : false)
      );
    }
  },
  methods: {
    ...mapActions("cliente", ["getClientes", "deleteCliente", "RESET_CLIENTE"]),
    ...mapMutations("cliente", ["SET_CLIENTE", "RESET_CLIENTE"]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    createCliente() {
      this.RESET_CLIENTE();
      this.SHOW_MODAL(false);
      // this.$router
      //   .push({
      //     path: "/clientes/create"
      //   })
      //   .catch(err => {
      //     throw new Error(`Surgió el siguiente error: ${err}.`);
      //   });
    },
    editCliente(item) {
      this.SET_CLIENTE(item);
      this.SHOW_MODAL(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteClient() {
      this.deleteCliente(this.idSelected);
      this.modalDelete = false;
    }
  },
  created() {
    this.getClientes();
  }
};
</script>

<style>
.links {
  text-decoration: none;
}
</style>
