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
          <base-material-stats-card-compania
            class="mx-3"
            :compania="capitalizeFirstLetter(c.nombre)"
            :smallValue="c.cantidad"
            title="Vigentes"
            :color="c.color"
            :subText="'A renovar: ' + c.renovar"
            :subIcon="c.renovar > 0 ? 'mdi-alert' : ''"
            :subIconColor="c.renovar >0 ? 'red' :''"
          ></base-material-stats-card-compania>
        </div>
        <base-material-stats-card-compania
          compania="Total"
          :smallValue="sumaTotal(companias_activas)"
          title="Vigentes"
          color="primary"
          :subText="'A renovar: ' + sumaTotalARenovar(companias_activas)"
          :subIcon="sumaTotalARenovar(companias_activas) > 0 ? 'mdi-alert' : ''"
          :subIconColor="sumaTotalARenovar(companias_activas) >0 ? 'red' :''"
        ></base-material-stats-card-compania>
      </v-row>
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
            :icon="cardIcon(r)"
          >
            <p
              class="
                        text-sm
                        mb-0
                        text-capitalize text-body
                        font-weight-light
                      "
              v-for="c in r.companias"
              :key="c.nombre"
            > {{capitalizeFirstLetter(c.nombre)}}: {{c.cantidad}}</p>
            <v-divider />
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
    ])
    // totalPolizas() {
    //   var total = 0;
    //   this.companias_activas.forEach(c => {
    //     if (c.cantidad > 0) total = total + c.cantidad;
    //   });
    //   return total;
    // }
  },
  methods: {
    ...mapActions("compania", ["getPolizasVigentes"]),
    sumaTotal(companias) {
      var total = 0;
      companias.forEach(c => {
        if (c.cantidad > 0) total = total + c.cantidad;
      });
      return total;
    },
    sumaTotalARenovar(companias) {
      var total = 0;
      companias.forEach(c => {
        if (c.renovar > 0) total = total + c.renovar;
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
    },
    cardIcon(item) {
      switch (item.tipo) {
        case 2:
          return "mdi-home";
        case 3:
          return "mdi-fire";
        case 4:
          return "mdi-alpha-r";
        case 9:
          return "mdi-alpha-s";
        case 10:
          return "mdi-alpha-c";
        case 5:
          return " mdi-storefront";
        case 6:
          return " mdi-medical-bag";
        case 11:
          return " mdi-truck";
        case 12:
          return " mdi-account-group";
        case 7:
          return "mdi-scooter";
        case 8:
          return " mdi-sail-boat";
      }
    },
    capitalizeFirstLetter(string) {
      string = string.toLowerCase();

      return string.replace(/(^\w{1})|(\s+\w{1})/g, letter =>
        letter.toUpperCase()
      );

      // string = string.toLowerCase();
      // return string[0].toUpperCase() + string.slice(1);
    }
  },
  created() {
    this.getPolizasVigentes();
  }
};
</script>

<style>
</style>