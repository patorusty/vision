<template>
  <v-card>
    <v-card-title>
      <span
        v-if="!edicion"
        class="headline"
      >Crear Compania</span>
      <span
        v-else
        class="headline"
      >Editar Compania</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-model="compania.nombre"
                :rules="[rules.required]"
                label="Nombre"
                v-uppercase
              >
              </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                v-model="compania.cuit"
                @keyup="buscarCuit"
                :rules="[
                  rules.required,
                  !cuitUsado || 'Este CUIT ya está en uso',
                ]"
                label="CUIT"
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                v-model="compania.codigo_lr"
                label="Libros Rubricados"
              ></v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0 d-flex justify-center">
              <v-switch
                v-model="compania.activo"
                label="Activo"
                inset
                primary
              ></v-switch>
            </v-col>
            <v-col class="pt-0 pb-0 d-flex justify-center">
              <v-avatar
                color="primary"
                size="62"
              >
                <span class="white--text headline">62</span>
              </v-avatar>
            </v-col>
            <!-- <v-col>
            <v-color-picker class="ma-2" hide-canvas></v-color-picker>
          </v-col> -->
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                v-model="compania.direccion"
                :rules="[rules.required]"
                label="Direccion"
              ></v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-autocomplete
                :rules="[rules.required]"
                :items="localidades"
                :item-text="localidadesText"
                item-value="id"
                v-model="compania.localidad_id"
                label="Localidad"
              ></v-autocomplete>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                :rules="[rules.required]"
                v-model="compania.telefono_1"
                label=" Telefono Oficina"
              ></v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                :rules="[rules.required]"
                v-model="compania.telefono_aux"
                label=" Telefono Auxilio"
              ></v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                :rules="[rules.required]"
                v-model="compania.telefono_siniestros"
                label=" Telefono Siniestros"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                :rules="[rules.email, rules.required]"
                label="Email Emision"
                v-model="compania.email_emision"
              ></v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                :rules="[rules.email, rules.required]"
                label="Email Siniestros"
                v-model="compania.email_siniestros"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions class=" pt-0 pb-0 d-flex justify-end">
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
import http from "../../../../http-request";
import { helpers } from "../../../../helpers";
import { debounce } from "debounce";

export default {
  mixins: [helpers],
  data: () => ({
    rules: {
      required: value => !!value || "Este campo obligatorio",
      email: value => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(value) || "Igrese un email válido";
      }
    },
    localidades: [],
    cuitUsado: false
  }),
  computed: {
    ...mapState("compania", ["compania"]),
    ...mapState("modal", ["modal", "edicion"])
  },
  methods: {
    ...mapActions("compania", [
      "getCompania",
      "updateCompania",
      "createCompania"
    ]),
    ...mapMutations("modal", ["HIDE_MODAL"]),
    ...mapMutations("compania", ["RESET_COMPANIA"]),
    async create() {
      if (this.$refs.form.validate()) {
        const createResult = await this.createCompania(this.compania);
        if (createResult) {
          this.closeModal();
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateCompania(this.compania);
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL(false);
      this.RESET_COMPANIA();
      this.$refs.form.resetValidation();
      this.cuitUsado = false;
    },
    buscarCuit: debounce(async function() {
      if (
        this.compania.cuit.length >= 6 &&
        this.compania.cuit != this.compania.cuitOriginal
      ) {
        const resp = await http.post("/companias/busquedaCuit", {
          cuit: this.compania.cuit
        });
        this.cuitUsado = resp.data.usado;
      }
    }, 700)
  },
  created() {
    this.cargarLocalidades();
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