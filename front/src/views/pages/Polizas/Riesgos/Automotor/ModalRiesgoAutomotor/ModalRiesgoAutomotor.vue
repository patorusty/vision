<template>
  <v-card>
    <v-card-title>
      <span v-if="edicion_ra">Editar Riesgo Automotor</span>
      <span v-else>Crear Riesgo Automotor</span>
    </v-card-title>
    <v-card-text>
      <v-form ref="form">
        <v-tabs vertical>
          <v-tab>
            <v-icon left>
              mdi-hammer-screwdriver
            </v-icon>
            Riesgo
          </v-tab>
          <v-tab>
            <v-icon left>
              mdi-gas-cylinder
            </v-icon>
            GNC
          </v-tab>
          <v-tab>
            <v-icon left>
              mdi-clipboard-text-outline
            </v-icon>
            A. Prendario
          </v-tab>
          <v-tab>
            <v-icon left>
              mdi-camera-plus-outline
            </v-icon>
            Fotos
          </v-tab>
          <v-tab-item>
            <tab-riesgo />
          </v-tab-item>
          <v-tab-item>
            <v-card flat>
              <v-card-text>
                <tab-gnc></tab-gnc>
              </v-card-text>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <tab-acreedor-prendario></tab-acreedor-prendario>
          </v-tab-item>
          <v-tab-item>
            <v-card flat>
              <v-card-text>
                <p>
                  Fusce a quam. Phasellus nec sem in justo pellentesque facilisis. Nam eget dui. Proin viverra, ligula sit amet ultrices semper, ligula arcu tristique sapien, a accumsan nisi mauris ac eros. In dui magna, posuere eget, vestibulum et, tempor auctor, justo.
                </p>
                <p class="mb-0">
                  Cras sagittis. Phasellus nec sem in justo pellentesque facilisis. Proin sapien ipsum, porta a, auctor quis, euismod ut, mi. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nam at tortor in tellus interdum sagittis.
                </p>
              </v-card-text>
            </v-card>
          </v-tab-item>
        </v-tabs>
      </v-form>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <v-btn
        class="mb-2"
        color="red"
        text
        @click="close"
      >Cerrar</v-btn>
      <v-btn
        class="mb-2"
        color="green"
        @click="create"
        text
        native-type="submit"
      >Crear</v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
import { mapState, mapMutations, mapActions } from "vuex";
import { helpers } from "../../../../../../helpers";
import TabRiesgo from "./Tabs/TabRiesgo";
import TabGNC from "./Tabs/TabGNC";
import TabAcreedorPrendario from "./Tabs/TabAcreedorPrendario";
export default {
  components: {
    "tab-gnc": TabGNC,
    TabRiesgo,
    TabAcreedorPrendario
  },
  mixins: [helpers],
  data: () => ({
    tipo_riesgo_id: null
  }),
  computed: {
    ...mapState("poliza", ["tipo_riesgos"]),
    ...mapState("modal", ["modal_ra", "edicion_ra"]),
    ...mapState("version", ["versiones", "modelo_id", "loading"]),
    ...mapState("marca", ["marcas"]),
    ...mapState("modelo", ["modelos", "marca_id"]),
    ...mapState("anio", ["anios"])
  },
  methods: {
    ...mapActions("modelo", ["updateMarcaId"]),
    ...mapMutations("version", ["UPDATE_MODELO_ID"]),
    ...mapMutations("modal", ["HIDE_MODAL_RA"]),
    ...mapMutations("riesgo", ["RESET_RIESGO_AUTOMOTOR"]),
    create() {
      if (this.$refs.form.validate()) {
        // const createResult = await this.createCompania(this.compania);
        // if (createResult) {
        //   this.closeModal();
      }
    },
    update() {
      if (this.$refs.form.validate()) {
        // const createResult = await this.createCompania(this.compania);
        // if (createResult) {
        //   this.closeModal();
        // }
      }
    },
    close() {
      this.HIDE_MODAL_RA(false);
      this.RESET_RIESGO_AUTOMOTOR();
      // this.$refs.form.resetValidation();
    }
  }
};
</script>

<style scoped>
</style>