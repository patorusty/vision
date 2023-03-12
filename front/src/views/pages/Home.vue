<template>
  <div class="mx-4  pa-3" v-if="!loading">
    <v-row>
      <v-col cols="4" class="py-0">
        <v-card-subtitle class="mb-8">
          Polizas Automotores
        </v-card-subtitle>
        <base-material-card color="success" class="mt-0" width="350" icon="mdi-car">
          <v-row>
            <v-data-table class="mx-3 pa-2 elevation-0" :headers="headers" :items-per-page="10" :items="polizas"
              multi-sort loading-text='Cargando...' :hide-default-footer=true>
              <template v-slot:[`item.compania`]="{ item }">
                <div>{{ capitalizeFirstLetter(item.nombre) }}</div>
              </template>
              <template v-slot:[`item.arenovar`]="{ item }">
                <v-btn text class="pa-0" @click="irARenovar(item.id)" color="error" plain=false><span
                    class="subtitle-2">{{
                      item.renovar }}</span>
                </v-btn>
              </template>
            </v-data-table>
          </v-row>
        </base-material-card>
      </v-col>
      <v-col class="py-0">
        <v-card-subtitle>
          Polizas Otros Riesgos
        </v-card-subtitle>
        <v-row class="d-flex space-end">
          <div v-for="r in companias_activas_or" :key="r.nombre">
            <base-material-card class="mx-3" width="190" inline :color="colors(r)" :icon="cardIcon(r)">
              <div :key="c.nombre" v-for="c in r.companias">
                <span class=" text-sm mb-0 text-capitalize text-body font-weight-light ">
                  {{ capitalizeFirstLetter(c.nombre) }}: {{ c.cantidad }}
                </span>
                <span v-if="c.renovar > 0">
                  <v-icon class="mb-1" size="14" color="red">mdi-alert</v-icon><span
                    class="caption grey--text font-weight-light"> A renovar: {{ c.renovar }}</span>
                </span>
              </div>
              <v-divider />
              <p class=" text-sm mb-0 text-capitalize text-body font-weight-regular "> Total: {{ sumaTotal(r.companias) }}
              </p>
            </base-material-card>
          </div>
        </v-row>
      </v-col>
    </v-row>

    <!-- <v-row>
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
      </v-row> -->
  </div>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  computed: {
    ...mapState("compania", [
      "companias_activas",
      "loading",
      "companias_activas_or",
      "loadingHome"
    ]),
    polizas() {
      var polizas = this.companias_activas;
      var totalPolizas = this.sumaTotal(this.companias_activas);
      var totalPolizasRonovar = this.sumaTotalARenovar(this.companias_activas);
      return [
        ...polizas,
        {
          nombre: "Total",
          cantidad: totalPolizas,
          renovar: totalPolizasRonovar,
          id: 0
        }
      ];
    },
    headers() {
      return [
        {
          text: "Compania",
          value: "compania",
          sortable: false,
          align: "start"
        },
        {
          text: "Vigentes",
          value: "cantidad",
          sortable: false
        },
        { text: "A renovar", value: "arenovar", sortable: false, align: 'center' }
      ];
    }
  },
  methods: {
    ...mapActions("compania", ["getPolizasVigentes"]),
    ...mapMutations('poliza', ["SET_SEARCH"]),
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
    },
    irARenovar(companiaId) {
      this.SET_SEARCH({
        tipo_riesgo_id: 1,
        poliza: "",
        patente: "",
        compania_id: companiaId,
        cliente: "",
        cliente_id: 0,
        siniestro: "",
        anio: null,
        filtroEstado: [4],
        filtroFormaPago: [],
        estado: "Abierto",
        tipo_cobertura: null
      })
      this.$router.push({ name: "Polizas" });
    }
  },
  created() {
    this.getPolizasVigentes();
  }
};
</script>

<style></style>