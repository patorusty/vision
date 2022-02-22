<template>
  <div>
    <v-card class="mt-0 mx-4 pa-3">
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
                  v-model="compania.cuit"
                  @keyup="buscarCuit"
                  :rules="[
                    rules.required,
                    !cuitUsado || 'Este CUIT ya está en uso',
                  ]"
                  label="CUIT"
                  v-uppercase
                >
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col class="pt-0 pb-0">
                <v-text-field
                  v-model="compania.codigo_lr"
                  label="Libros Rubricados"
                  v-uppercase
                ></v-text-field>
              </v-col>
              <v-col class="d-flex justify-center pt-0 pb-0">
                <v-switch
                  v-model="compania.activo"
                  label="Activo"
                  primary
                ></v-switch>
              </v-col>
              <v-col class="d-flex justify-center pt-0 pb-0">
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
                  v-model="compania.direccion"
                  :rules="[rules.required]"
                  label="Direccion"
                  v-uppercase
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
                  :rules="[rules.required]"
                  v-model="compania.telefono_1"
                  label=" Telefono Oficina"
                  v-uppercase
                ></v-text-field>
              </v-col>
              <v-col class="pt-0 pb-0">
                <v-text-field
                  :rules="[rules.required]"
                  v-model="compania.telefono_aux"
                  label=" Telefono Auxilio"
                  v-uppercase
                ></v-text-field>
              </v-col>
              <v-col class="pt-0 pb-0">
                <v-text-field
                  :rules="[rules.required]"
                  v-model="compania.telefono_siniestros"
                  label=" Telefono Siniestros"
                  v-uppercase
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col class="pt-0 pb-0">
                <v-text-field
                  :rules="[rules.email, rules.required]"
                  label="Email Emision"
                  v-model="compania.email_emision"
                  v-uppercase
                ></v-text-field>
              </v-col>
              <v-col class="pt-0 pb-0">
                <v-text-field
                  :rules="[rules.email, rules.required]"
                  label="Email Siniestros"
                  v-model="compania.email_siniestros"
                  v-uppercase
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-container>
      </v-card-text>
      <v-card-actions class="d-flex justify-end">
        <v-btn
          class="mb-2"
          color="red"
          @click="volver"
          text
        >Volver</v-btn>
        <v-btn
          class="mb-2"
          @click="update"
          text
          color="green"
        >Guardar</v-btn>
      </v-card-actions>
    </v-card>
    <codigo-organizador />
    <codigo-productor />
    <coberturas />
  </div>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import http from "../../../../http-request";
import { helpers } from "../../../../helpers";
import { debounce } from "debounce";
import CodigoOrganizador from "./CodigoOrganizador/CodigoOrganizador";
import CodigoProductor from "./CodigoProductor/CodigoProductor";
import Coberturas from "./Coberturas/Coberturas";

export default {
  components: {
    CodigoOrganizador,
    CodigoProductor,
    Coberturas
  },
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
    ...mapState("compania", ["compania"])
  },
  methods: {
    ...mapActions("compania", [
      "getCompania",
      "updateCompania",
      "getCompanias"
    ]),

    ...mapMutations("compania", ["RESET_COMPANIA"]),

    volver() {
      this.$router.push({
        name: "Compañías"
      });
      this.RESET_COMPANIA();
    },
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateCompania(this.compania);
      }
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
    this.getCompanias();
    this.cargarLocalidades();
    this.getCompania(this.$route.params.nombre);
  }
};
</script>

<style>
</style>