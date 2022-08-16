<template>
  <div>
    <v-card
      color="transparent"
      elevation="0"
      class="mt-0 mx-4 pa-3"
      v-if="!loading"
    >
      <v-card-subtitle>
        Polizas Automotor
      </v-card-subtitle>
      <v-row>
        <div
          v-for="c in companias_activas"
          :key="c.id"
        >
          <base-material-card
            class="mx-3"
            v-if="c.cantidad > 0"
            width="190"
            inline
            :color="c.color"
          >
            <template v-slot:heading>
              <div class="display-2 font-weight-light">
                {{c.nombre}}
              </div>
            </template>
            <v-card-text class="subtitle-1 font-weight-light"> Polizas Vigentes: {{c.cantidad}}</v-card-text>
          </base-material-card>
        </div>
        <base-material-card
          class="mx-3"
          width="230"
          inline
          color="primary"
        >
          <template v-slot:heading>
            <div class="display-2 font-weight-light">
              TOTAL
            </div>
          </template>
          <v-card-text class="subtitle-1 font-weight-light"> Total Polizas Vigentes: {{totalPolizas}}</v-card-text>
        </base-material-card>
      </v-row>
      <!-- </v-card>
    <v-card
      color="transparent"
      elevation="0"
      v-if="!loadingHome"
      class="mt-0 mx-4 pa-3"
    > -->
      <v-card-subtitle>
        Polizas Otros Riesgos
      </v-card-subtitle>
      <v-row>
        <div
          v-for="r in companias_activas_or"
          :key="r.nombre"
        >
          <base-material-card
            class="mx-3"
            width="190"
            inline
            :color="colors(r)"
          >
            <template v-slot:heading>
              <v-icon v-if="r.tipo == 2">mdi-home</v-icon>
              <v-icon v-else-if="r.tipo == 3"> mdi-fire </v-icon>
              <span v-else-if="r.tipo == 4">
                <v-icon>mdi-alpha-r </v-icon>
                <v-icon> mdi-alpha-c </v-icon>
              </span>
              <span v-else-if="r.tipo == 9">
                <v-icon>mdi-alpha-s </v-icon>
                <v-icon> mdi-alpha-t </v-icon>
              </span>
              <span v-else-if="r.tipo == 10">
                <v-icon>mdi-alpha-c</v-icon>
                <v-icon> mdi-alpha-a </v-icon>
                <v-icon> mdi-alpha-u </v-icon>
              </span>
              <v-icon v-else-if="r.tipo == 5"> mdi-storefront </v-icon>
              <v-icon v-else-if="r.tipo == 6"> mdi-medical-bag </v-icon>
              <v-icon v-else-if="r.tipo == 11"> mdi-truck </v-icon>
              <v-icon v-else-if="r.tipo == 12"> mdi-account-group </v-icon>
              <v-icon v-else-if="r.tipo == 7">mdi-scooter</v-icon>
              <v-icon v-else-if="r.tipo == 8"> mdi-sail-boat </v-icon>
            </template>
            <p
              class="
                        text-sm
                        mb-0
                        text-capitalize text-body
                        font-weight-light
                      "
              v-for="c in r.companias"
              :key="c.nombre"
            > {{c.nombre}}: {{c.cantidad}}</p>
            <p class="
                        text-sm
                        mb-0
                        text-capitalize text-body
                        font-weight-regular
                      "> Total: {{sumaTotal(r.companias)}}</p>
          </base-material-card>
        </div>
      </v-row>
    </v-card>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  computed: {
    ...mapState("compania", [
      "companias_activas",
      "loading",
      "companias_activas_or",
      "loadingHome"
    ]),
    totalPolizas() {
      var total = 0;
      this.companias_activas.forEach(c => {
        if (c.cantidad > 0) total = total + c.cantidad;
      });
      return total;
    }
  },
  methods: {
    ...mapActions("compania", ["getPolizasVigentes", "getPolizasORVigentes"]),
    sumaTotal(companias) {
      var total = 0;
      companias.forEach(c => {
        if (c.cantidad > 0) total = total + c.cantidad;
      });
      return total;
    },
    colors(item) {
      switch (item.tipo) {
        case 2:
          return "#53CFDA";
        case 3:
          return "#FFC900";
        case 4:
          return "#FF3747";
        case 5:
          return "#00C3AF";
        case 6:
          return "#EF39A7";
        case 7:
          return "#FFD600";
        case 8:
          return "#F1C9C2";
        case 9:
          return "#2492CC";
        case 10:
          return "#FFAE90";
        case 11:
          return "#EAE45F";
        case 12:
          return "#FF3747";
        default:
          break;
      }
    }
  },
  created() {
    this.getPolizasVigentes();
    this.getPolizasORVigentes();
  }
};
</script>

<style>
</style>