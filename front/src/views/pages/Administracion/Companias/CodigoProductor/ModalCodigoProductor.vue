<template>
  <v-card>
    <v-card-title>
      <span>Codigo Productor</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col>
              <v-select
                label="Seleccione Productor"
                v-model="codigo_productor.productor_id"
                :items="productores"
                :item-text="codigoText"
                item-value="id"
                :rules="[rules.required]"
              >
              </v-select>
            </v-col>
          </v-row>

          <v-row>
            <v-col>
              <v-select
                label="Seleccione Código Organizador"
                v-model="codigo_productor.codigo_organizador_id"
                :items="co_activos"
                :item-text="codigoOrgText"
                item-value="id"
                :rules="[rules.required]"
              >
              </v-select>
            </v-col>
          </v-row>

          <v-row>
            <v-col>
              <v-text-field
                v-uppercase
                label="Codigo Productor"
                v-model="codigo_productor.codigo_productor"
                :rules="[
                  rules.required,
                  !cpUsado || 'Este codigo productor ya está en uso',
                ]"
                @keyup="buscarCP"
              >
              </v-text-field>
            </v-col>
            <v-col>
              <v-switch
                v-model="codigo_productor.activo"
                label="Codigo Productor Activo?"
              ></v-switch>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <v-btn
        class="mb-2"
        color="red"
        text
        @click="closeModal"
      >Cerrar</v-btn>
      <v-btn
        class="mb-2"
        v-if="!edicion2"
        color="green"
        @click="create"
        text
        native-type="submit"
      >Crear</v-btn>
      <v-btn
        class="mb-2"
        v-else
        @click="update"
        text
        color="green"
      >Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../../helpers";
import http from "../../../../../http-request";
import { debounce } from "debounce";

export default {
  mixins: [helpers],
  data: () => ({
    rules: {
      required: value => !!value || "Este campo obligatorio"
    },
    cpUsado: false
  }),
  computed: {
    ...mapState("codigo_productor", ["codigo_productor"]),
    ...mapState("compania", ["compania"]),
    ...mapState("productor", ["productores"]),
    ...mapState("organizador", ["organizadores"]),
    ...mapState("codigo_organizador", ["codigo_organizadores"]),
    ...mapState("modal", ["modal2", "edicion2"]),
    co_activos() {
      return this.codigo_organizadores.filter(co => co.activo == 1);
    }
  },
  methods: {
    ...mapActions("codigo_productor", [
      "updateCodigoProductor",
      "createCodigoProductor"
    ]),
    ...mapMutations("modal", ["HIDE_MODAL2"]),
    ...mapMutations("codigo_productor", ["RESET_CODIGO_PRODUCTOR"]),
    async create() {
      if (this.$refs.form.validate()) {
        this.codigo_productor.compania_id = this.compania.id;
        const createResult = await this.createCodigoProductor(
          this.codigo_productor
        );
        if (createResult) {
          this.closeModal();
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateCodigoProductor(
          this.codigo_productor
        );
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL2(false);
      this.RESET_CODIGO_PRODUCTOR();
      this.$refs.form.resetValidation();
      this.cpUsado = false;
    },
    buscarCP: debounce(async function() {
      if (
        this.codigo_productor.codigo_productor &&
        this.codigo_productor.codigo_productor !=
          this.codigo_productor.cpOriginal
      ) {
        const resp = await http.post("/codigo_productor/busquedaCP", {
          codigo_productor: this.codigo_productor.codigo_productor,
          codigo_organizador_id: this.codigo_productor.codigo_organizador_id,
          compania_id: this.compania.id
        });
        this.cpUsado = resp.data.usado;
      }
    }, 500)
  },
  watch: {
    modal2() {
      if (!this.modal2) {
        this.closeModal();
      }
    }
  }
};
</script>

<style>
</style>