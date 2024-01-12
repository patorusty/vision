<template>
  <v-card class=" mx-4 pa-3">
    <v-card-title v-if="poliza.tipo_de_riesgo">
      {{ poliza.tipo_de_riesgo.nombre }}
      <v-spacer></v-spacer>
      <v-btn icon @click="isFormDisabled = !isFormDisabled">
        <v-icon>{{ isFormDisabled ? "mdi-lock-outline" : "mdi-lock-open-outline" }}</v-icon>
      </v-btn>
    </v-card-title>
    <v-card-title v-else>
      Otro Riesgo
    </v-card-title>
    <v-card-text>
      <v-form :disabled="isFormDisabled">
        <v-row
          v-if="poliza.tipo_riesgo_id == 3 || poliza.tipo_riesgo_id == 4 || poliza.tipo_riesgo_id == 5 || poliza.tipo_riesgo_id == 10 || poliza.tipo_riesgo_id == 9 || poliza.tipo_riesgo_id == 6 || poliza.tipo_riesgo_id == 11 || poliza.tipo_riesgo_id == 12 || poliza.tipo_riesgo_id == 13 || poliza.tipo_riesgo_id == 14">
          <v-col cols="4">
            <v-text-field label="Actividad" v-model="otro_riesgo.actividad" v-uppercase>
            </v-text-field>
          </v-col>
        </v-row>
        <div
          v-if="poliza.tipo_riesgo_id == 4 || poliza.tipo_riesgo_id == 5 || poliza.tipo_riesgo_id == 2 || poliza.tipo_riesgo_id == 3">
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field label="Calle" v-model="otro_riesgo.direccion" v-uppercase>
              </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0" :cols="3">
              <v-text-field label="Número" v-model="otro_riesgo.nro" v-uppercase>
              </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0" :cols="1">
              <v-text-field label="Piso" v-model="otro_riesgo.piso" v-uppercase>
              </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0" :cols="1">
              <v-text-field label="Depto" v-model="otro_riesgo.depto" v-uppercase>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field label="Barrio / Country" v-model="otro_riesgo.barrio" v-uppercase>
              </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0" :cols="2">
              <v-text-field label="Lote" v-model="otro_riesgo.lote" v-uppercase> </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-autocomplete :items="localidades" :item-text="localidadesText" item-value="id"
                v-model="otro_riesgo.localidad_id" label="Localidad"></v-autocomplete>
            </v-col>
          </v-row>
        </div>
        <v-row>
          <v-col v-if="poliza.tipo_riesgo_id == 7">
            <v-select v-model="otro_riesgo.tipo" :items="tipos_or" item-value="value" item-text="text"
              label="Tipo"></v-select>
          </v-col>
          <v-col v-if="poliza.tipo_riesgo_id == 8">
            <v-text-field label="Tipo de embarcacion" v-model="otro_riesgo.tipo_embarcacion" v-uppercase> </v-text-field>
          </v-col>
          <v-col v-if="poliza.tipo_riesgo_id == 8">
            <v-text-field label="Matricula" v-model="otro_riesgo.matricula" v-uppercase> </v-text-field>
          </v-col>
          <v-col v-if="poliza.tipo_riesgo_id == 7 || poliza.tipo_riesgo_id == 8 || poliza.tipo_riesgo_id == 9">
            <v-text-field label="Suma Asegurada" v-model="otro_riesgo.suma_asegurada" v-uppercase> </v-text-field>
          </v-col>
          <v-col v-if="poliza.tipo_riesgo_id == 7 || poliza.tipo_riesgo_id == 9 || poliza.tipo_riesgo_id == 8">
            <v-text-field label="Cobertura" v-model="otro_riesgo.cobertura" v-uppercase> </v-text-field>
          </v-col>
          <v-col v-if="poliza.tipo_riesgo_id == 8">
            <v-autocomplete :items="localidades" :item-text="localidadesText" item-value="id"
              v-model="otro_riesgo.localidad_id" label="Localidad"></v-autocomplete>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-textarea label="Detalle" v-model="otro_riesgo.detalle">
            </v-textarea>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    <v-card-actions class="mt-0 py-0 d-flex justify-end">
      <v-btn v-if="poliza.otro_riesgo && Object.keys(poliza.otro_riesgo).length > 0" class="mb-2" color="green" text
        @click="update" native-type="submit">Guardar</v-btn>
      <v-btn v-else class="mb-2" color="green" text @click="create" native-type="submit">Crear</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../../helpers";

export default {
  mixins: [helpers],
  data: () => ({
    localidades: [],
    isFormDisabled: true
  }),
  computed: {
    ...mapState("riesgo", ["otro_riesgo", "tipos_or"]),
    ...mapState("poliza", ["poliza"])
  },
  methods: {
    ...mapActions("riesgo", ["createOtroRiesgo", "updateOtroRiesgo"]),

    async create() {
      const riesgo = {
        ...this.otro_riesgo,
        ...{
          poliza_id: this.poliza.id,
          tipo_riesgo_id: this.poliza.tipo_riesgo_id
        }
      };
      const respCreate = await this.createOtroRiesgo(riesgo);
      const updateResp = await this.updateOtroRiesgo(this.otro_riesgo);
      if (updateResp) {
        this.isFormDisabled = true;
      }
    },
    async update() {
      const updateResp = await this.updateOtroRiesgo(this.otro_riesgo);
      if (updateResp) {
        this.isFormDisabled = true;
      }
    }
  },
  created() {
    this.cargarLocalidades();
    if (!this.poliza.otro_riesgo) {
      this.isFormDisabled = false;
    }
  }
};
</script>

<style></style>