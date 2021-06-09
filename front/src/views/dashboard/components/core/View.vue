<template>
  <v-content>
    <router-view class="mb" />

    <dashboard-core-footer />
  </v-content>
</template>

<script>
export default {
  name: "DashboardCoreView",

  components: {
    DashboardCoreFooter: () => import("./Footer")
  }
};
</script>

<style scoped>
.mb {
  margin-bottom: 110px !important;
}
</style>
