  <template>
  <v-card>
    <!-- <v-card-title>
      <span>Dos</span>
    </v-card-title> -->
    <v-card-text>
      <v-tabs v-model="tabModal">
        <v-tab
          v-for="riesgo in riesgo_automotores"
          v-bind:key="riesgo.id"
        >{{riesgo.patente}}</v-tab>
      </v-tabs>
      <v-tabs-items v-model="tabModal">
        <v-tab-item
          v-for="riesgo in riesgo_automotores"
          v-bind:key="riesgo.id"
        >
          <v-form ref="form">
            <v-container>
              <v-row>
                <v-col>
                  <div class="text-subtitle-1">
                    {{"Marca: " + riesgo.marca.nombre}}
                  </div>
                </v-col>
                <v-col>
                  <div class="text-subtitle-1">
                    {{"Modelo: " + riesgo.version.nombre}}
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <div class="text-subtitle-1">
                    {{"Año: " + riesgo.anio.anio}}
                  </div>
                </v-col>
                <v-col>
                  <div class="text-subtitle-1">
                    {{"Patente: " + riesgo.patente}}
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <v-text-field
                    label="Valor del Vehiculo"
                    v-model="riesgo.valor_vehiculo"
                  ></v-text-field>
                </v-col>
                <v-col>
                  <v-autocomplete
                    :items="coberturas"
                    item-text="nombre"
                    item-value="id"
                    label="Cobertura"
                    v-model="riesgo.cobertura_id"
                    :rules="[rules.required]"
                  ></v-autocomplete>
                </v-col>
                <v-col>
                  <v-select
                    label="Ajuste"
                    :items="ajustes"
                    v-model="riesgo.ajuste"
                  ></v-select>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
          <v-card-actions class="d-flex justify-end">
            <v-btn
              class="mb-2"
              color="red"
              text
              @click="atras"
            >Atras</v-btn>
            <v-btn
              class="mb-2"
              @click="update(riesgo)"
              text
              color="green"
            >Guardar</v-btn>
            <v-btn
              class="mb-2"
              color="red"
              text
              @click="closeModal"
            >Cerrar</v-btn>
          </v-card-actions>
        </v-tab-item>
      </v-tabs-items>
    </v-card-text>

  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { helpers } from "../../../../../../helpers";

export default {
  data: () => ({
    tabModal: null,
    rules: {
      required: value => !!value || "Este campo obligatorio"
    }
  }),
  computed: {
    ...mapState("riesgo", [
      "riesgo_automotores",
      "tipo_vehiculos",
      "tipo_patentes",
      "combustibles",
      "estados",
      "usos",
      "tipo_cerrocerias",
      "ajustes",
      "equipos_rastreo"
    ]),
    ...mapState("cobertura", ["coberturas"])
  },
  mixins: [helpers],
  methods: {
    ...mapActions("riesgo", ["updateRiesgoAutomotor"]),
    ...mapMutations("poliza", ["RESET_POLIZA"]),
    ...mapMutations("modal", ["HIDE_MODAL", "SET_STEP"]),
    closeModal() {
      this.HIDE_MODAL(false), this.SET_STEP(1);
    },
    async update(riesgo) {
      // if (this.$refs.form.validate()) {
      const createResult = await this.updateRiesgoAutomotor(riesgo);
      // }
    },
    atras() {
      this.SET_STEP(1);
    }
  }
};
</script>

<style>
</style>