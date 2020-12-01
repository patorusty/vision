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
      <v-btn color="primary" @click="SHOW_MODAL(false)" dark>Crear</v-btn>
      <v-dialog
        @click:outside="HIDE_MODAL(false)"
        :value="modal"
        max-width="70%"
      >
        <modal-companias></modal-companias>
      </v-dialog>
    </v-card-title>
    <v-data-table
      class="pa-2"
      :headers="headers"
      :items-per-page="5"
      :items="companias"
      :search="search"
      multi-sort
      :loading="loading"
    >
      <template slot="item.activo" slot-scope="props">{{
        textoActivo(props.item.activo)
      }}</template>
      <template v-slot:[`item.actions`]="{ item }">
        <router-link
          class="links"
          :to="{ name: 'Editar Companias', params: { nombre: item.nombre } }"
        >
          <v-icon
            small
            class="mr-2"
            @click="editCompania(item.nombre)"
            color="success"
          >
            mdi-pencil
          </v-icon>
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
          <span>Esta seguro que desea eliminar esta Compania?</span>
        </v-card-text>
        <v-card-actions class="py-0 pt-3 pr-6 d-flex justify-end">
          <v-btn dark color="red" @click="modalDelete = false">Cancelar</v-btn>
          <v-btn class="ml-4" dark color="success" @click="deleteCompany"
            >Confirmar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../helpers";
import ModalCompanias from "./ModalCompanias.vue";
export default {
  components: {
    ModalCompanias,
  },
  mixins: [helpers],
  data: () => ({
    search: "",
    idSelected: null,
    modalDelete: false,
    headers: [
      { text: "Nombre", value: "nombre" },
      { text: "Telefono", value: "telefono_1" },
      { text: "Tel. Auxilio", value: "telefono_aux" },
      { text: "Tel. Siniestro", value: "telefono_siniestros" },
      { text: "Activo", value: "activo" },
      { text: "Actions", value: "actions", sortable: false, align: "right" },
    ],
  }),
  computed: {
    ...mapState("compania", ["companias", "loading"]),
    ...mapState("modal", ["modal"]),
  },
  methods: {
    ...mapActions("compania", [
      "getCompanias",
      "getCompania",
      "deleteCompania",
    ]),
    ...mapMutations("modal", ["SHOW_MODAL", "HIDE_MODAL"]),
    editCompania(nombre) {
      this.$router
        .push({
          path: `/administracion/companias/${nombre}`,
        })
        .catch((err) => {
          throw new Error(`Surgió el siguiente error: ${err}.`);
        });
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteCompany() {
      this.deleteCompania(this.idSelected);
      this.modalDelete = false;
    },
  },
  created() {
    this.getCompanias();
  },
};
</script>

<style>
.links {
  text-decoration: none;
}
</style>