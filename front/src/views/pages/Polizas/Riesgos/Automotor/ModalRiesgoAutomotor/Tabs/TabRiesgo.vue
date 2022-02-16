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
            v-model="riesgo_automotor.automotor_tipo"
          ></v-select>
          <v-autocomplete
            class="mt-6"
            :items="anios"
            item-text="anio"
            item-value="id"
            label="Año"
            v-model="riesgo_automotor.automotor_anio_id"
            @input="updateAnio"
            :rules="[rules.required]"
            return_object
            clearable
          ></v-autocomplete>
          <v-autocomplete
            class="mt-6"
            :items="marcas"
            item-text="nombre"
            item-value="id"
            label="Marca"
            v-model="riesgo_automotor.automotor_marca_id"
            @input="updateMarca"
            :rules="[rules.required]"
            clearable
            no-data-text="No se encontraron registros"
          ></v-autocomplete>
          <v-autocomplete
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
            @input="updateModelo"
            no-data-text="No se encontraron registros"
          ><template
              v-if="searchModelo"
              v-slot:no-data
            >
              <p class="grey--text text--darken-1 pa-2 mb-0">
                "{{ searchModelo | upper }}" no existe, click &nbsp;
                <a @click="createModel(searchModelo)">AQUI</a>
                &nbsp;para crearla
              </p>
            </template></v-autocomplete>
          <v-autocomplete
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
          <v-row v-if="riesgo_automotor.automotor_tipo != 'Motovehiculo'">
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
                v-mask="'AAA###'"
                v-model="riesgo_automotor.patente"
                :rules="[rules.required]"
                v-uppercase
              ></v-text-field>
              <v-text-field
                v-else
                placeholder="AB123CD"
                v-mask="'AA###AA'"
                v-model="riesgo_automotor.patente"
                :rules="[rules.required]"
                v-uppercase
              ></v-text-field>
            </v-col>
          </v-row>
          <v-text-field
            v-else
            placeholder="AB123CD"
            v-model="riesgo_automotor.patente"
            :rules="[rules.required]"
            v-uppercase
          ></v-text-field>
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
            v-if="riesgo_automotor.automotor_tipo != 'Motovehiculo'"
            label="Tipo Carroceria"
            v-model="riesgo_automotor.tipo_carroceria"
            :items="tipo_carrocerias"
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
    ...mapState("marca", ["marcas"]),
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
    }
  },
  methods: {
    ...mapMutations("riesgo", ["UPDATE_RIESGO_AUTOMOTOR", "UPDATE_KV"]),
    ...mapMutations("anio", ["SET_ANIO"]),
    ...mapActions("modelo", ["getModelosPorMArca", "createModelo"]),
    ...mapMutations("modelo", ["UPDATE_MARCA_ID"]),
    ...mapActions("version", ["createVersion", "getVersionesPorModeloYanio"]),
    ...mapMutations("version", [
      "RESET_VERSION",
      "UPDATE_MODELO_ID",
      "UPDATE_ANIO_ID"
    ]),
    async createModel(nombre) {
      const model = {
        nombre: nombre.trim(),
        automotor_marca_id: this.marca_id
      };
      const createResult = await this.createModelo(model);
      if (createResult) {
        this.UPDATE_KV({ automotor_modelo_id: this.modelo.id });
      }
    },
    async crearVersion(nombre) {
      const vers = { nombre: nombre, automotor_modelo_id: this.modelo.id };
      const createResult = await this.createVersion(vers);
      if (createResult) {
        this.UPDATE_KV({ automotor_version_id: this.version.id });
      }
    },
    change() {
      this.riesgo_automotor.patente = null;
    },
    updateAnio(a) {
      this.SET_ANIO({ id: a, anio: a });
    },
    updateMarca() {
      this.UPDATE_MARCA_ID(this.riesgo_automotor.automotor_marca_id);
      this.getModelosPorMArca();
    },
    updateModelo() {
      this.UPDATE_MODELO_ID(this.riesgo_automotor.automotor_modelo_id);
      this.UPDATE_ANIO_ID(this.riesgo_automotor.automotor_anio_id);
      this.getVersionesPorModeloYanio();
    }
  },
  watch: {
    "riesgo_automotor.automotor_marca_id": function() {
      if (this.riesgo_automotor.automotor_marca_id == null) {
        this.riesgo_automotor.automotor_modelo_id = null;
        this.riesgo_automotor.automotor_version_id = null;
      }
    },
    "riesgo_automotor.automotor_modelo_id": function() {
      if (this.riesgo_automotor.automotor_modelo_id == null) {
        this.riesgo_automotor.automotor_version_id = null;
      }
    },
    "riesgo_automotor.automotor_anio_id": function() {
      if (this.riesgo_automotor.automotor_anio_id == null) {
        this.riesgo_automotor.automotor_modelo_id = null;
        this.riesgo_automotor.automotor_marca_id = null;
        this.riesgo_automotor.automotor_version_id = null;
      }
    }
  }
};
</script>

<style>
</style>