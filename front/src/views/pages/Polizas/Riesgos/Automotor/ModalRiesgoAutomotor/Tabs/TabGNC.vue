<template>
  <v-card flat>
    <v-card-subtitle class="py-0">
      Cubiertas
    </v-card-subtitle>
    <v-card-text>
      <v-row>
        <v-col
          class="py`-0"
          cols="4"
        >
          <v-select
            :items="cubiertas"
            v-model="riesgo_automotor.cubiertas_marca"
            label="Marca"
          ></v-select>
        </v-col>
        <v-col
          class="py`-0"
          cols="4"
        >
          <v-text-field
            label="Medida"
            v-model="riesgo_automotor.cubiertas_medida"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-subtitle class="py-0 d-flex flex-row align-center"><span>GNC</span>
      <v-checkbox
        v-model="riesgo_automotor.gnc"
        class="ms-2"
      ></v-checkbox>
    </v-card-subtitle>
    <v-card-text>
      <v-row>
        <v-col class="py`-0">
          <v-text-field
            :disabled="riesgo_automotor.gnc == false"
            v-model="riesgo_automotor.gnc_nro_oblea"
            label="Nro Oblea"
          ></v-text-field>
        </v-col>
        <v-col class="py`-0">
          <v-text-field
            v-model="riesgo_automotor.gnc_marca_cilindro"
            :disabled="riesgo_automotor.gnc == false"
            label="Marca Cilindro"
          ></v-text-field>
        </v-col>
        <v-col class="py`-0">
          <v-text-field
            v-model="riesgo_automotor.gnc_marca_regulador"
            :disabled="riesgo_automotor.gnc == false"
            label="Marca Regulador"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col class="py`-0">
          <v-menu
            v-model="calendarioVencimientoOblea"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                :disabled="riesgo_automotor.gnc == false"
                :value="formatDate(riesgo_automotor.gnc_vencimiento_oblea)"
                @click:clear="
                          $nextTick(() => (riesgo_automotor.gnc_vencimiento_oblea = null))
                        "
                clearable
                label="Vencimiento Oblea"
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="riesgo_automotor.gnc_vencimiento_oblea"
              :disabled="riesgo_automotor.gnc == false"
              clearable
              color="primary lighten-1"
              no-title
              scrollable
              locale="es-la"
              @input="calendarioVencimientoOblea = false"
            >
            </v-date-picker>
          </v-menu>
        </v-col>
        <v-col class="py`-0">
          <v-text-field
            v-model="riesgo_automotor.gnc_nro_cilindro"
            :disabled="riesgo_automotor.gnc == false"
            label="Nro Cilindro"
            v-uppercase
          ></v-text-field>
        </v-col>
        <v-col class="py`-0">
          <v-text-field
            v-model="riesgo_automotor.gnc_nro_regulador"
            :disabled="riesgo_automotor.gnc == false"
            label="Nro Regulador"
            v-uppercase
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col
          class="py`-0"
          cols="4"
        >
          <v-text-field
            v-model="riesgo_automotor.valor_gnc"
            :disabled="riesgo_automotor.gnc == false"
            label="Valor GNC"
            v-uppercase
          ></v-text-field>
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-subtitle class="py-0">
      Accesorios
    </v-card-subtitle>
    <v-card-text>
      <v-row>
        <v-col
          class="py`-0"
          cols="4"
        >
          <v-text-field
            v-model="riesgo_automotor.accesorio_01"
            label="Accesorio Nro 1"
            v-uppercase
          ></v-text-field>
        </v-col>
        <v-col
          class="py`-0"
          cols="4"
        >
          <v-text-field
            v-model="riesgo_automotor.valor_accesorio_01"
            label="Valor Nro 1"
            v-uppercase
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col
          class="py`-0"
          cols="4"
        >
          <v-text-field
            v-model="riesgo_automotor.accesorio_02"
            label="Accesorio Nro 2"
            v-uppercase
          ></v-text-field>
        </v-col>
        <v-col
          class="py`-0"
          cols="4"
        >
          <v-text-field
            v-model="riesgo_automotor.valor_accesorio_02"
            label="Valor Nro 2"
            v-uppercase
          ></v-text-field>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapState } from "vuex";
import { helpers } from "../../../../../../../helpers";
export default {
  mixins: [helpers],
  data: () => ({
    calendarioVencimientoOblea: false,
    cubiertas: [
      {
        value: "Pirelli",
        text: "Pirelli"
      },
      {
        value: "Fate",
        text: "Fate"
      },
      {
        value: "Continental",
        text: "Continental"
      },
      {
        value: "Michelin",
        text: "Michelin"
      },
      {
        value: "Dunlop",
        text: "Dunlop"
      },
      {
        value: "Goodyear",
        text: "Goodyear"
      },
      {
        value: "Hankook",
        text: "Hankook"
      },
      {
        value: "Firestone",
        text: "Firestone"
      },
      {
        value: "Bridgestone",
        text: "Bridgestone"
      },
      {
        value: "Yokohama",
        text: "Yokohama"
      },
      {
        value: "Otro",
        text: "Otro"
      }
    ]
  }),
  computed: {
    ...mapState("riesgo", ["riesgo_automotor"])
  }
};
</script>

<style>
</style>