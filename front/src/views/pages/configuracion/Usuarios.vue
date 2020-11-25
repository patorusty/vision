<template>
  <v-container>
    <v-card>
      <v-card-title>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-btn color="primary" @click="SHOW_MODAL(false)" dark>Crear</v-btn>
        <v-dialog v-model="modal" persistent max-width="600px">
          <modal-usuarios> </modal-usuarios>
        </v-dialog>
      </v-card-title>
      <v-data-table
        class="pa-2"
        :headers="headers"
        :items-per-page="5"
        :items="usuarios"
        :search="search"
        multi-sort
      >
        <template slot="item.activo" slot-scope="props">{{
          textoActivo(props.item.activo)
        }}</template>
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="editUsuario(item.id)"
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
          <v-dialog max-width="30%" v-model="modalDelete">
            <v-card class="pa-4">
              <v-card-title>
                <v-row>
                  <v-col>Esta seguro que desea eliminar este usuario?</v-col>
                </v-row>
              </v-card-title>
              <v-row>
                <v-col class="py-0 pt-3 pr-6 d-flex justify-end">
                  <v-btn dark color="red" @click="modalDelete = false"
                    >Cancelar</v-btn
                  >
                  <v-btn class="ml-4" dark color="success" @click="deleteUser"
                    >Confirmar</v-btn
                  >
                </v-col>
              </v-row>
            </v-card>
          </v-dialog>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>
<script>
import ModalUsuarios from "./ModalUsuarios";
import { mapState, mapActions, mapMutations } from "vuex";

export default {
  components: {
    ModalUsuarios
  },
  data() {
    return {
      modalDelete: false,
      search: "",
      idSelected: null,
      headers: [
        { text: "Apellido", value: "apellido" },
        { text: "Nombre", value: "nombre" },
        { text: "Mail", value: "email" },
        { text: "Tipo Usuario", value: "tipo_usuario.nombre" },
        { text: "Activo", value: "activo" },
        { text: "Actions", value: "actions", sortable: false, align: "right" }
      ]
    };
  },
  computed: {
    ...mapState("usuarios", ["usuarios"]),
    ...mapState("modal", ["modal"]),
  },
  methods: {
    ...mapActions("usuarios", ["getUsuarios", "getUsuario", "deleteUsuario"]),
    ...mapMutations("modal", ["SHOW_MODAL"]),
    editUsuario(id) {
      this.getUsuario(id);
      this.SHOW_MODAL(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteUser() {
      this.deleteUsuario(this.idSelected);
      this.modalDelete = false;
    },
    textoActivo(nro) {
      return nro === 1 ? "Activo" : "Inactivo";
    }
  },
  created() {
    // this.getUsuarios();
  }
};
</script>
