<template>
  <v-card flat>
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
            :rules="[rules.required]"
          ></v-autocomplete>
          <v-autocomplete
            class="mt-6"
            :items="marcas"
            item-text="nombre"
            item-value="id"
            label="Marca"
            v-model="riesgo_automotor.automotor_marca_id"
            @input="updateMarcaId"
            :rules="[rules.required]"
          ></v-autocomplete>
          <v-autocomplete
            class="mt-6"
            :items="modelosFiltrados"
            item-text="nombre"
            item-value="id"
            label="Modelo"
            @input="UPDATE_MODELO_ID"
            v-model="riesgo_automotor.automotor_modelo_id"
            :rules="[rules.required]"
          ></v-autocomplete>
          <v-autocomplete
            class="mt-6"
            :items="versionesFiltradas"
            item-text="nombre"
            item-value="id"
            label="Version"
            v-model="riesgo_automotor.automotor_version_id"
            :rules="[rules.required]"
          ></v-autocomplete>
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
          <v-row>
            <v-col class="py-0">
              <v-select
                :items="tipo_patentes"
                label="Tipo Patente"
                v-model="riesgo_automotor.tipo_patente"
              ></v-select>
            </v-col>
            <v-col class="py-0">
              <v-text-field
                v-model="riesgo_automotor.patente"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-text-field
            label="Motor"
            v-model="riesgo_automotor.nro_motor"
            :rules="[rules.required]"
          ></v-text-field>
          <v-text-field
            label="Chasis"
            v-model="riesgo_automotor.nro_chasis"
            :rules="[rules.required]"
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
          ></v-text-field>
          <v-text-field
            label="Valor Total"
            v-model="suma"
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
    tipo_vehiculos: [
      {
        value: "Automotor",
        text: "Automotor"
      },
      {
        value: "Camion",
        text: "Camion"
      },
      {
        value: "Trailer",
        text: "Trailer"
      }
    ],
    tipo_patentes: [
      {
        value: 0,
        text: "Nacional"
      },
      {
        value: 1,
        text: "Mercosur"
      }
    ],
    combustibles: [
      {
        value: "Nafta",
        text: "Nafta"
      },
      {
        value: "Diesel",
        text: "Diesel"
      },
      {
        value: "GNC",
        text: "GNC"
      }
    ],
    estados: [
      {
        value: "Muy Bueno",
        text: "Muy Bueno"
      },
      {
        value: "Bueno",
        text: "Bueno"
      },
      {
        value: "Regular",
        text: "Regular"
      }
    ],
    usos: [
      {
        value: "Particular",
        text: "Particular"
      },
      {
        value: "Comercial",
        text: "Comercial"
      },
      {
        value: "Particular / Comercial",
        text: "Particular / Comercial"
      },
      {
        value: "Remise",
        text: "Remise"
      },
      {
        value: "Cabify / Uber",
        text: "Cabify / Uber"
      }
    ],
    tipo_carrocerias: [
      {
        value: "Sedan 3 Puertas",
        text: "Sedan 3 Puertas"
      },
      {
        value: "Sedan 4 Puertas",
        text: "Sedan 4 Puertas"
      },
      {
        value: "Sedan 5 Puertas",
        text: "Sedan 5 Puertas"
      },
      {
        value: "Rural",
        text: "Rural"
      },
      {
        value: "Utilitario",
        text: "Utilitario"
      },
      {
        value: "Berlina",
        text: "Berlina"
      },
      {
        value: "Coupe",
        text: "Coupe"
      },
      {
        value: "Cabriolet",
        text: "Cabriolet"
      }
    ],
    ajustes: [
      {
        value: "0%",
        text: "0%"
      },
      {
        value: "10%",
        text: "10%"
      },
      {
        value: "20%",
        text: "20%"
      },
      {
        value: "30%",
        text: "30%"
      }
    ],
    equipos_rastreo: [
      {
        value: "No",
        text: "No"
      },
      {
        value: "Lo Jack",
        text: "Lo Jack"
      },
      {
        value: "Ituran",
        text: "Ituran"
      },
      {
        value: "Otro",
        text: "Otro"
      }
    ]
  }),
  computed: {
    ...mapState("riesgo", ["riesgo_automotor"]),
    ...mapState("version", ["versiones", "modelo_id"]),
    ...mapState("marca", ["marcas"]),
    ...mapState("modelo", ["modelos", "marca_id"]),
    ...mapState("anio", ["anios"]),
    ...mapState("cobertura", ["coberturas"]),
    suma() {
      return (
        parseInt(this.riesgo_automotor.valor_vehiculo) +
        parseInt(this.riesgo_automotor.valor_gnc) +
        parseInt(this.riesgo_automotor.valor_accesorio_01) +
        parseInt(this.riesgo_automotor.valor_accesorio_02)
      );
    }
  },
  methods: {
    ...mapActions("modelo", ["updateMarcaId"]),
    ...mapMutations("version", ["UPDATE_MODELO_ID"])
  }
};
</script>

<style>
</style>