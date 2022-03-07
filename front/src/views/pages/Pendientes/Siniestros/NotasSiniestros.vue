<template>
  <div>
    <v-toolbar
      color="transparent"
      elevation='0'
    >
      <v-toolbar-title class="title">Notas</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn
        class="mt-5"
        icon
        @click="SHOW_MODAL4(false)"
      >
        <v-icon color="primary">mdi-plus</v-icon>
      </v-btn>
      <v-dialog
        max-width='500'
        v-model="modal4"
        @click:outside="HIDE_MODAL4(false)"
        @keydown.esc="HIDE_MODAL4(false)"
      >
        <modal-nota-siniestros />
      </v-dialog>
    </v-toolbar>
    <v-list
      style="max-height: 350px"
      class="overflow-y-auto"
      flat
    >
      <v-list-item-group>
        <template v-for="(nota, i) in notas_siniestro">
          <v-list-item
            :key="nota.id"
            :selectable=false
          >
            <v-list-item-content>
              <v-list-item-title class="text-wrap">{{dateToString(nota.fecha)}} - {{ nota.nota }}</v-list-item-title>
            </v-list-item-content>
            <v-list-item-action class="d-flex flex-row align-center">
              <v-icon
                small
                class="mr-2"
                color="success"
                @click="editNota(nota.id)"
              >
                mdi-pencil
              </v-icon>
              <v-icon
                class="ml-2"
                small
                color="error"
                @click="openDeleteModal(nota.id)"
              >
                mdi-close
              </v-icon>
            </v-list-item-action>
          </v-list-item>
          <v-divider :key="i"></v-divider>
        </template>
      </v-list-item-group>
    </v-list>
    <v-dialog
      :retain-focus="false"
      max-width="30%"
      v-model="modalDelete"
    >
      <v-card class="pa-4">
        <v-card-text>
          <span>Esta seguro que desea eliminar esta Nota?</span>
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
            @click="deleteN"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../helpers";
import ModalNotaSiniestros from "./ModalNotasSiniestros.vue";
export default {
  mixins: [helpers],
  components: { ModalNotaSiniestros },
  data: () => ({
    idSelected: "",
    modalDelete: false
  }),
  computed: {
    ...mapState("modal", ["modal4"]),
    ...mapState("nota_siniestro", ["notas_siniestro"])
  },
  methods: {
    ...mapMutations("modal", ["SHOW_MODAL4", "HIDE_MODAL4"]),
    ...mapActions("nota_siniestro", ["getNota", "deleteNota"]),
    editNota(id) {
      this.getNota(id);
      this.SHOW_MODAL4(true);
    },
    openDeleteModal(id) {
      this.idSelected = id;
      this.modalDelete = true;
    },
    deleteN() {
      this.deleteNota(this.idSelected);
      this.modalDelete = false;
      this.idSelected = "";
    }
  }
};
</script>

<style scoped>
.title {
  font-weight: 300;
  font-size: 18px !important;
  display: inline-block;
  text-align: center;
  width: 100%;
  padding: 24px 24px 0;
}
</style>