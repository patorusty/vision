<template>
  <v-card flat>
    <v-card-subtitle class="py-0">
      Vehiculo
    </v-card-subtitle>
    <v-card-text>
      <v-row>
        <v-col class="d-flex flex-column justify-space-between">
          <v-select
            :items="tipo_vehiculos"
            label="Tipo de Vehiculo"
            item-text="nombre"
            item-value="id"
            v-model="riesgo_automotor.tipo_automotor_id"
          ></v-select>
          <v-autocomplete
            type="text"
            hide-spin-buttons
            class="mt-6"
            :items="anios"
            item-text="anio"
            item-value="id"
            label="Año"
            :search-input.sync="searchAnio"
            v-model="riesgo_automotor.automotor_anio_id"
            :rules="[rules.required]"
            clearable
            menu-props="closeOnContentClick"
            @input="updateAnio"
            no-data-text="No se encontraron registros"
          ><template
              v-if="searchAnio"
              v-slot:no-data
            >
              <p class="grey--text text--darken-1 pa-2 mb-0">
                "{{ searchAnio }}" no existe, click &nbsp;
                <a @click="createYear(searchAnio)">AQUI</a>
                &nbsp;para crearlo
              </p>
            </template>
          </v-autocomplete>
          <v-autocomplete
            ref="marcas"
            class="mt-6"
            :items="marcas"
            item-text="nombre"
            item-value="id"
            label="Marca"
            :search-input.sync="searchMarca"
            @update:search-input="toUpper('searchMarca')"
            v-model="riesgo_automotor.automotor_marca_id"
            :rules="[rules.required]"
            clearable
            menu-props="closeOnContentClick"
            @change="updateMarca"
            no-data-text="No se encontraron registros"
          ><template
              v-if="searchMarca"
              v-slot:no-data
            >
              <p class="grey--text text--darken-1 pa-2 mb-0">
                "{{ searchMarca | upper }}" no existe, click &nbsp;
                <a @click="createBrand(searchMarca)">AQUI</a>
                &nbsp;para crearla
              </p>
            </template></v-autocomplete>
          <v-autocomplete
            ref="modelos"
            class="mt-6"
            :items="modelos"
            item-text="nombre"
            item-value="id"
            label="Modelo"
            :search-input.sync="searchModelo"
            @update:search-input="toUpper('searchModelo')"
            v-model="riesgo_automotor.automotor_modelo_id"
            :rules="[rules.required]"
            clearable
            menu-props="closeOnContentClick"
            @change="updateModelo"
            no-data-text="No se encontraron registros"
          ><template
              v-if="searchModelo"
              v-slot:no-data
            >
              <p class="grey--text text--darken-1 pa-2 mb-0">
                "{{ searchModelo | upper }}" no existe, click &nbsp;
                <a @click="createModel(searchModelo)">AQUI</a>
                &nbsp;para crearlo
              </p>
            </template></v-autocomplete>
          <v-autocomplete
            ref="versiones"
            class="mt-6"
            :items="versiones"
            item-text="nombre"
            item-value="id"
            label="Version"
            :search-input.sync="searchVersion"
            @update:search-input="toUpper('searchVersion')"
            v-model="riesgo_automotor.automotor_version_id"
            :rules="[rules.required]"
            clearable
            menu-props="closeOnContentClick"
            no-data-text="No se encontraron registros"
          ><template
              v-if="searchVersion"
              v-slot:no-data
            >
              <p class="grey--text text--darken-1 pa-2 mb-0">
                "{{ searchVersion | upper }}" no existe, click &nbsp;
                <a @click.prevent="crearVersion(searchVersion)">AQUI</a>
                &nbsp;para crearla
              </p>
            </template>
          </v-autocomplete>
          <v-autocomplete
            class="mt-6"
            :items="coberturas"
            item-text="nombre"
            item-value="id"
            label="Cobertura"
            v-model="riesgo_automotor.cobertura_id"
            :rules="[rules.required]"
          ></v-autocomplete>
        </v-col>
        <v-col class="d-flex flex-column justify-space-between">
          <v-text-field
            v-if="riesgo_automotor.tipo_automotor_id == 2"
            placeholder="A123BCD"
            v-model="riesgo_automotor.patente"
            :rules="[rules.required]"
            v-uppercase
          ></v-text-field>
          <v-row v-if="riesgo_automotor.tipo_automotor_id == 4 ">
            <v-col class="py-0">
              <v-select
                :items="tipo_patentes"
                label="Tipo Patente"
                v-model="riesgo_automotor.tipo_patente"
              ></v-select>
            </v-col>
            <v-col class="py-0">
              <v-text-field
                v-if="riesgo_automotor.tipo_patente == 0"
                placeholder="101ABC123"
                v-mask="'###AAA###'"
                v-model="riesgo_automotor.patente"
                :rules="[rules.required]"
                v-uppercase
              ></v-text-field>
              <v-text-field
                v-else
                placeholder="101AB123CD"
                v-mask="'###AA###AA'"
                v-model="riesgo_automotor.patente"
                :rules="[rules.required]"
                v-uppercase
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row v-else>
            <v-col class="py-0">
              <v-select
                :items="tipo_patentes"
                label="Tipo Patente"
                v-model="riesgo_automotor.tipo_patente"
              ></v-select>
            </v-col>
            <v-col class="py-0">
              <v-text-field
                v-if="riesgo_automotor.tipo_patente == 0"
                placeholder="ABC123"
                :v-mask="riesgo_automotor.tipo_automotor_id == 4 ? '###AAA###' : 'AAA###'"
                v-model="riesgo_automotor.patente"
                :rules="[rules.required]"
                v-uppercase
              ></v-text-field>
              <v-text-field
                v-else
                placeholder="AB123CD"
                :v-mask="riesgo_automotor.tipo_automotor_id == 4 ? '###AA###AA' : 'AA###AA'"
                v-model="riesgo_automotor.patente"
                :rules="[rules.required]"
                v-uppercase
              ></v-text-field>
            </v-col>
          </v-row>

          <v-text-field
            label="Motor"
            v-model="riesgo_automotor.nro_motor"
            :rules="[rules.required]"
            v-uppercase
          ></v-text-field>
          <v-text-field
            label="Chasis"
            v-model="riesgo_automotor.nro_chasis"
            :rules="[rules.required]"
            v-uppercase
          ></v-text-field>
          <v-text-field
            label="Valor Vehiculo"
            v-model="riesgo_automotor.valor_vehiculo"
            :rules="[rules.required]"
          ></v-text-field>
          <v-row>
            <v-col class="py-0">
              <v-checkbox
                label="0km"
                v-model="riesgo_automotor.okm"
              ></v-checkbox>
            </v-col>
            <v-col class="py-0">
              <v-select
                label="Ajuste"
                :items="ajustes"
                v-model="riesgo_automotor.ajuste"
              ></v-select>
            </v-col>
          </v-row>
          <v-select
            :items="equipos_rastreo"
            label="Equipo de Rastreo"
            v-model="riesgo_automotor.equipo_rastreo"
          ></v-select>
        </v-col>
        <v-col class="d-flex flex-column justify-space-between">
          <v-select
            label="Uso"
            v-model="riesgo_automotor.uso"
            :items="usos"
          ></v-select>
          <v-select
            label="Tipo Carroceria"
            item-text="nombre"
            item-value="id"
            v-model="riesgo_automotor.tipo_carroceria_id"
            :items="carroceriasFiltradas"
            :rules="[rules.required]"
          ></v-select>
          <v-select
            label="Combustible"
            v-model="riesgo_automotor.combustible"
            :items="combustibles"
          ></v-select>
          <v-select
            label="Estado General"
            v-model="riesgo_automotor.estado_general"
            :items="estados"
          ></v-select>
          <v-text-field
            label="Color"
            v-model="riesgo_automotor.color"
            v-uppercase
          ></v-text-field>
          <v-text-field
            label="Valor Total"
            v-model="valorTotal"
          ></v-text-field>
          <v-textarea
            rows="2"
            label="Observaciones"
            v-model="riesgo_automotor.observaciones"
          ></v-textarea>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
import { helpers } from "../../../../../../../helpers";
export default {
  mixins: [helpers],
  data: () => ({
    searchAnio: null,
    searchMarca: null,
    searchModelo: null,
    searchVersion: null
  }),
  computed: {
    ...mapState("riesgo", [
      "riesgo_automotor",
      "tipo_vehiculos",
      "tipo_patentes",
      "combustibles",
      "estados",
      "usos",
      "tipo_carrocerias",
      "ajustes",
      "equipos_rastreo"
    ]),
    ...mapState("version", ["version", "versiones", "modelo_id"]),
    ...mapState("marca", ["marcas", "marca"]),
    ...mapState("modelo", ["modelos", "marca_id", "modelo"]),
    ...mapState("anio", ["anio", "anios"]),
    ...mapState("cobertura", ["coberturas"]),
    valorTotal: {
      get: function() {
        return this.suma(this.riesgo_automotor);
      },
      set: function() {
        return this.suma(this.riesgo_automotor);
      }
    },
    carroceriasFiltradas() {
      switch (this.riesgo_automotor.tipo_automotor_id) {
        case 1:
          return this.tipo_carrocerias.filter(
            c =>
              c.id == 2 ||
              c.id == 3 ||
              c.id == 4 ||
              c.id == 6 ||
              c.id == 8 ||
              c.id == 12 ||
              c.id == 13 ||
              c.id == 14 ||
              c.id == 23 ||
              c.id == 24
          );
        case 2:
          return this.tipo_carrocerias.filter(c => c.id == 9);
        case 3:
          return this.tipo_carrocerias.filter(c => c.id == 18);
        case 4:
          return this.tipo_carrocerias.filter(c => c.id == 17);
        case 5:
          return this.tipo_carrocerias.filter(c => c.id == 25);
        default:
          break;
      }
    }
  },
  methods: {
    ...mapMutations("riesgo", ["UPDATE_RIESGO_AUTOMOTOR", "UPDATE_KV"]),
    ...mapMutations("anio", ["SET_ANIO"]),
    ...mapActions("anio", ["createAnio"]),
    ...mapActions("marca", ["createMarca"]),
    ...mapActions("modelo", ["getModelosPorMarca", "createModelo"]),
    ...mapMutations("modelo", ["UPDATE_MARCA_ID"]),
    ...mapActions("version", ["createVersion", "getVersionesPorModelo"]),
    ...mapMutations("version", [
      "RESET_VERSION",
      "UPDATE_MODELO_ID",
      "UPDATE_ANIO_ID"
    ]),
    async createYear(anio) {
      const a = {
        anio: anio.trim(),
        id: anio.trim()
      };
      const createResult = await this.createAnio(a);
      if (createResult) {
        this.UPDATE_KV({ automotor_anio_id: this.anio.anio });
      }
    },
    async createBrand(nombre) {
      const marca = {
        nombre: nombre.trim()
      };
      const createResult = await this.createMarca(marca);
      if (createResult) {
        this.$refs.modelos.reset();
        this.UPDATE_KV({ automotor_marca_id: this.marca.id });
        this.updateMarca();
      }
    },
    async createModel(nombre) {
      const model = {
        nombre: nombre.trim(),
        automotor_marca_id: this.riesgo_automotor.automotor_marca_id
      };
      const createResult = await this.createModelo(model);
      if (createResult) {
        this.UPDATE_KV({ automotor_modelo_id: this.modelo.id });
        this.updateModelo();
      }
    },
    async crearVersion(nombre) {
      const vers = {
        nombre: nombre,
        automotor_modelo_id: this.riesgo_automotor.automotor_modelo_id
      };
      const createResult = await this.createVersion(vers);
      if (createResult) {
        this.UPDATE_KV({ automotor_version_id: this.version.id });
      }
    },
    updateAnio(a) {
      this.SET_ANIO({ id: a, anio: a });
    },
    updateMarca() {
      this.UPDATE_MARCA_ID(this.riesgo_automotor.automotor_marca_id);
      this.getModelosPorMarca(this.riesgo_automotor.automotor_marca_id);
      this.riesgo_automotor.automotor_modelo_id = null;
      this.$refs.modelos.reset();
    },
    updateModelo() {
      this.UPDATE_MODELO_ID(this.riesgo_automotor.automotor_modelo_id);
      this.riesgo_automotor.automotor_version_id = null;
      this.$refs.versiones.reset();
      this.getVersionesPorModelo(this.riesgo_automotor.automotor_modelo_id);
    }
  },
  watch: {
    "riesgo_automotor.automotor_marca_id": function() {
      if (this.riesgo_automotor.automotor_marca_id == null) {
        this.riesgo_automotor.automotor_modelo_id = null;
        this.$refs.modelos.reset();
        this.riesgo_automotor.automotor_version_id = null;
        this.$refs.versiones.reset();
      }
    },
    "riesgo_automotor.automotor_modelo_id": function() {
      if (this.riesgo_automotor.automotor_modelo_id == null) {
        this.riesgo_automotor.automotor_version_id = null;
        this.$refs.versiones.reset();
      }
    },
    "riesgo_automotor.automotor_anio_id": function() {
      if (this.riesgo_automotor.automotor_anio_id == null) {
        this.riesgo_automotor.automotor_modelo_id = null;
        this.$refs.modelos.reset();
        this.riesgo_automotor.automotor_marca_id = null;
        this.$refs.marcas.reset();
        this.riesgo_automotor.automotor_version_id = null;
        this.$refs.versiones.reset();
      }
    }
  }
};
</script>

<style>
</style>