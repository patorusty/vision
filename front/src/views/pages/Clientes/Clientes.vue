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
      <v-btn color="primary" @click="createCliente" dark>Crear</v-btn>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="10"
      :items="clientes"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template v-slot:[`item.full_name`]="{ item }">
        {{
          item.razon_social
            ? item.razon_social
            : item.nombre + " " + item.apellido
        }}
      </template>
      <template v-slot:[`item.documento`]="{ item }">
        {{ item.cuit ? item.cuit : item.nro_dni }}
      </template>
      <template v-slot:[`item.productor`]="{ item }"
        >{{ item.productores.nombre }} {{ item.productores.apellido }}</template
      >

      <template slot="item.activo" slot-scope="props">{{
        textoActivo(props.item.activo)
      }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <router-link
          class="links"
          :to="{ name: 'Editar Cliente', params: { id: item.id } }"
        >
          <v-icon small class="mr-2" color="success"> mdi-pencil </v-icon>
        </router-link>
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
    <v-dialog :retain-focus="false" max-width="30%" v-model="modalDelete">
      <v-card class="pa-4">
        <v-card-text>
          <span>Esta seguro que desea eliminar esta Cliente?</span>
        </v-card-text>
        <v-card-actions class="py-0 pt-3 pr-6 d-flex justify-end">
          <v-btn dark color="red" @click="modalDelete = false">Cancelar</v-btn>
          <v-btn class="ml-4" dark color="success" @click="deleteCliente"
            >Confirmar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../helpers";
export default {
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: null,
    modalDelete: false,
    headers: [
      { text: "Nombre", value: "full_name" },
      { text: "DNI / CUIT", value: "documento" },
      { text: "Celular", value: "celular" },
      { text: "E-mail", value: "email" },
      { text: "Productor", value: "productor" },
      { text: "Actions", value: "actions", sortable: false, align: "right" },
    ],
  }),
  computed: {
    ...mapState("cliente", ["clientes", "loading"]),
    ...mapState("modal", ["modal"]),
  },
  methods: {
    ...mapActions("cliente", ["getClientes", "deleteCliente"]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    createCliente() {
      this.$router
        .push({
          path: "/clientes/create",
        })
        .catch((err) => {
          throw new Error(`Surgió el siguiente error: ${err}.`);
        });
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteCliente() {
      this.deleteCliente(this.idSelected);
      this.modalDelete = false;
    },
  },
  created() {
    this.getClientes();
  },
};
</script>

<style>
.links {
  text-decoration: none;
}
</style>