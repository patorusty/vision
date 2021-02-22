<template>
  <v-card>
    <v-card-title>
      <span>Codigo Organizador</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col>
              <v-select
                label="Seleccione Organizador"
                v-model="codigo_organizador.organizador_id"
                :items="organizadores"
                :item-text="codigoText"
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
                label="Codigo Organizador"
                v-model="codigo_organizador.codigo_organizador"
                :rules="[
                  rules.required,
                  !coUsado || 'Este codigo organizador ya está en uso',
                ]"
                @keyup="buscarCO"
              >
              </v-text-field>
            </v-col>
            <v-col>
              <v-switch
                v-model="codigo_organizador.activo"
                label="Codigo Organizador Activo?"
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
        v-if="!edicion"
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
    coUsado: false
  }),
  computed: {
    ...mapState("codigo_organizador", ["codigo_organizador"]),
    ...mapState("compania", ["compania"]),
    ...mapState("organizador", ["organizadores"]),
    ...mapState("modal", ["modal", "edicion"])
  },
  methods: {
    ...mapActions("codigo_organizador", [
      "resetCodigoOrganizadorState",
      "updateCodigoOrganizador",
      "createCodigoOrganizador"
    ]),
    ...mapMutations("modal", ["HIDE_MODAL"]),
    ...mapMutations("codigo_organizador", ["RESET_CODIGO_ORGANIZADOR"]),
    async create() {
      if (this.$refs.form.validate()) {
        this.codigo_organizador.compania_id = this.compania.id;
        const createResult = await this.createCodigoOrganizador(
          this.codigo_organizador
        );
        if (createResult) {
          this.closeModal();
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateCodigoOrganizador(
          this.codigo_organizador
        );
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL(false);
      this.RESET_CODIGO_ORGANIZADOR();
      this.$refs.form.resetValidation();
      this.coUsado = false;
    },
    buscarCO: debounce(async function() {
      if (
        this.codigo_organizador.codigo_organizador &&
        this.codigo_organizador.codigo_organizador !=
          this.codigo_organizador.coOriginal
      ) {
        const resp = await http.post("/codigo_organizador/busquedaCO", {
          codigo: this.codigo_organizador.codigo_organizador,
          compania_id: this.compania.id
        });
        this.coUsado = resp.data.usado;
      }
    }, 500)
  },
  watch: {
    modal() {
      if (!this.modal) {
        this.closeModal();
      }
    }
  }
};
</script>

<style>
</style>