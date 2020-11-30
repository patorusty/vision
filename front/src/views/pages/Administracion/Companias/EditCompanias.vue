<template>
  <div>
    <v-card class="mt-0 mx-4 pa-3">
      <v-card-text>
        <v-container>
          <v-form ref="form">
            <v-row>
              <v-col>
                <v-text-field
                  v-model="compania.nombre"
                  :rules="[rules.required]"
                  label="Nombre"
                >
                </v-text-field>
              </v-col>
              <v-col>
                <v-text-field
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
              <v-col>
                <v-text-field
                  v-model="compania.codigo_lr"
                  label="Libros Rubricados"
                ></v-text-field>
              </v-col>
              <v-col class="d-flex justify-center">
                <v-switch
                  v-model="compania.activo"
                  label="Activo"
                  primary
                ></v-switch>
              </v-col>
              <v-col class="d-flex justify-center">
                <v-avatar color="primary" size="62">
                  <span class="white--text headline">62</span>
                </v-avatar>
              </v-col>
              <!-- <v-col>
            <v-color-picker class="ma-2" hide-canvas></v-color-picker>
          </v-col> -->
            </v-row>
            <v-row>
              <v-col>
                <v-text-field
                  v-model="compania.direccion"
                  :rules="[rules.required]"
                  label="Direccion"
                ></v-text-field>
              </v-col>
              <v-col>
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
              <v-col>
                <v-text-field
                  :rules="[rules.required]"
                  v-model="compania.telefono_1"
                  label=" Telefono Oficina"
                ></v-text-field>
              </v-col>
              <v-col>
                <v-text-field
                  :rules="[rules.required]"
                  v-model="compania.telefono_aux"
                  label=" Telefono Auxilio"
                ></v-text-field>
              </v-col>
              <v-col>
                <v-text-field
                  :rules="[rules.required]"
                  v-model="compania.telefono_siniestros"
                  label=" Telefono Siniestros"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-text-field
                  :rules="[rules.email, rules.required]"
                  label="Email Emision"
                  v-model="compania.email_emision"
                ></v-text-field>
              </v-col>
              <v-col>
                <v-text-field
                  :rules="[rules.email, rules.required]"
                  label="Email Siniestros"
                  v-model="compania.email_siniestros"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-container>
      </v-card-text>
      <!-- <v-card-actions class="d-flex justify-end">
      <v-btn class="mb-2" color="red" text @click="closeModal">Cerrar</v-btn>
      <v-btn
        class="mb-2"
        v-if="!edicion"
        color="green"
        @click="create"
        text
        native-type="submit"
        >Crear</v-btn
      >
      <v-btn class="mb-2" v-else @click="update" text color="green"
        >Guardar</v-btn
      >
    </v-card-actions> -->
    </v-card>
    <codigo-organizador />
    <codigo-productor />
  </div>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import http from "../../../../http-request";
import { helpers } from "../../../../helpers";
import { debounce } from "debounce";
import CodigoOrganizador from "./CodigoOrganizador/CodigoOrganizador";
import CodigoProductor from './CodigoProductor/CodigoProductor';

export default {
  components: {
    CodigoOrganizador,
    CodigoProductor
  },
  mixins: [helpers],
  data: () => ({
    rules: {
      required: (value) => !!value || "Este campo obligatorio",
      email: (value) => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(value) || "Igrese un email válido";
      },
    },
    localidades: [],
    cuitUsado: false,
  }),
  computed: {
    ...mapState("compania", ["compania"]),
    ...mapState("modal", ["modal", "edicion"]),
  },
  methods: {
    ...mapActions("compania", ["getCompania", "updateCompania"]),

    ...mapMutations("modal", ["HIDE_MODAL"]),

    ...mapMutations("compania", ["RESET_COMPANIA"]),
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateCompania(this.compania);
        if (updateResult) {
          console.log("update");
        }
      }
    },

    buscarCuit: debounce(async function () {
      if (
        this.compania.cuit.length >= 6 &&
        this.compania.cuit != this.compania.cuitOriginal
      ) {
        const resp = await http.post("/companias/busquedaCuit", {
          cuit: this.compania.cuit,
        });
        this.cuitUsado = resp.data.usado;
      }
    }, 700),
    async cargarLocalidades() {
      const resp = await http.get("/localidades");
      this.localidades = resp.data;
    },
  },
  created() {
    this.cargarLocalidades();
    this.getCompania(this.$route.params.nombre);
  },
};
</script>

<style>
</style>